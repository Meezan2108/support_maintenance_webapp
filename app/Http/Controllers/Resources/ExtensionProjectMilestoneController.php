<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Granttchartable;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExtensionProjectMilestoneController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            "proposal_id" => ["required", "numeric"],
            "year" => ["nullable", "numeric"],
            "quarter" => ["nullable", "numeric", Rule::in([1, 2, 3, 4])],
            "exclude_application_id" => ['nullable', 'array'],
            "exclude_application_id.*" => ['numeric', Rule::exists(ExtensionProject::class, 'id')]
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);
        $userAuth = User::find(Auth::id());

        if ($userAuth->hasRole(['Researcher']) && $userAuth->id != $proposal->user_id) {
            abort(403);
        }

        $applications = $proposal->extensionProject()
            ->with("granttchart")
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->get();

        $milestones = Granttchartable::query()
            ->where("granttchartable_type", ExtensionProject::class)
            ->whereIn("granttchartable_id", $applications->pluck("id")->toArray())
            ->get();

        return response([
            'message' => 'Search Users',
            'data' => [
                "applications" => $applications,
                "milestones" => $milestones
            ]
        ], 200);
    }
}
