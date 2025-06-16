<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "search_by" => ["nullable"],
            "for_kpi" => ["nullable", "in:1,0"]
        ]);

        $fieldAllowed = [
            "application_id",
            "project_number",
            "project_title",
            "project_number"
        ];

        $reqField = explode(",", $request->search_by);
        foreach ($reqField as $field) {
            if (!in_array($field, $fieldAllowed)) {
                throw ValidationException::withMessages([
                    "search_by" => 'field ' . $field . ' not allowed!'
                ]);
            }
        }

        $userAuth = User::find(Auth::id());

        $searchField = $reqField ?? ['application_id'];

        $concatStr = count($searchField) > 1
            ? "CONCAT(" . implode(", ' - ',", $searchField) . ")"
            : $searchField[0];

        $proposal = Proposal::query()
            ->where(function ($query) use ($searchField, $request) {
                foreach ($searchField as $field) {
                    $query->orWhere($field, 'LIKE', "%{$request->search}%");
                }
            })
            ->select('id', DB::raw("$concatStr as description"))
            ->when($userAuth->hasExactRoles(['Researcher']), function ($query) use ($userAuth) {
                return $query->where('user_id', $userAuth->id);
            })
            ->when($request->for_kpi, function ($query) {
                $query->whereIn(
                    "project_status",
                    [
                        Proposal::STATUS_PRJ_COMPLETED,
                        Proposal::STATUS_PRJ_ON_GOING,
                        Proposal::STATUS_PRJ_EXTENDED
                    ]
                );
            })
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Proposals',
            'data' => $proposal
        ], 200);
    }

    public function show(Request $request, Proposal $proposal)
    {
        $userAuth = User::find(Auth::id());

        if ($userAuth->hasRole(['researcher']) && $userAuth->id != $proposal->user_id) {
            abort(403);
        }

        $request->validate([
            "search_by" => ["nullable"]
        ]);

        $fieldAllowed = [
            "application_id",
            "project_number",
            "project_title",
            "project_number"
        ];

        if ($request->search_by) {
            $reqField = explode(",", $request->search_by);
            foreach ($reqField as $field) {
                if (!in_array($field, $fieldAllowed)) {
                    throw ValidationException::withMessages([
                        "search_by" => 'field ' . $field . ' not allowed!'
                    ]);
                }
            }
        }

        $searchField = $reqField ?? ['project_number'];

        $arrDescription = [];
        foreach ($searchField as $field) {
            $arrDescription[] = $proposal->{$field};
        }

        $proposal->description = implode(" - ", $arrDescription);

        return response([
            'message' => 'Show Proposal',
            'data' => $proposal->load(
                "researcher",
                "projectCost.detail",
                "projectCost.costSeries",
                "typeOfFund",
                "teams",
                "objectives",

                "researchType",
                "researchCluster",
                "seoCategory",
                "seoGroup",
                "seoArea",
                "primaryResearchField.category",
                "primaryResearchField.group",
                "primaryResearchField.area",
                "secondaryResearchField.category",
                "secondaryResearchField.group",
                "secondaryResearchField.area",
            )
        ], 200);
    }
}
