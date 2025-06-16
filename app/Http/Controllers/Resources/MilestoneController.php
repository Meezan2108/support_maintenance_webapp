<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Approvement;
use App\Models\Granttchartable;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "proposal_id" => ["required", "numeric"],
            "year" => ["nullable", "numeric"],
            "quarter" => ["nullable", "numeric", Rule::in([1, 2, 3, 4])],
            "type" => ["nullable", Rule::in(
                [
                    ProposalMilestone::TYPE_PROPOSAL,
                    ProposalMilestone::TYPE_EXTENSION
                ]
            )],
            "with_extension_milestone" => ['nullable', 'boolean']
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);
        $userAuth = User::find(Auth::id());

        if ($userAuth->hasRole(['Researcher']) && $userAuth->id != $proposal->user_id) {
            abort(403);
        }

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $request->proposal_id ?? false)
            ->when($request->year, function ($query, $year) {
                return $query->where(DB::raw("YEAR([from])"), $year);
            })
            ->when($request->year && $request->quarter, function ($query) use ($request) {
                return $query->filterByQuarter($request->year, $request->quarter);
            })
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->get();

        return response([
            'message' => 'Search Users',
            'data' => $milestones
        ], 200);
    }

    public function show(Request $request, Proposal $proposal)
    {
        $userAuth = User::find(Auth::id());

        if ($userAuth->hasRole(['Researcher']) && $userAuth->id != $proposal->user_id) {
            abort(403);
        }

        $request->validate([
            "search_by" => ["nullable", Rule::in(["application_id", "project_title", "project_number"])]
        ]);

        $searchField = $request->search_by ?? 'application_id';

        $proposal->description = $proposal->$searchField;

        return response([
            'message' => 'Show Users',
            'data' => $proposal->load("researcher")
        ], 200);
    }
}
