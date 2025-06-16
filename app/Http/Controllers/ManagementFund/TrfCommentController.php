<?php

namespace App\Http\Controllers\ManagementFund;

use App\Actions\ManagementFund\CreateComment;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\FormComment;
use App\Http\Requests\ManagementFund\FormCommentRequest;
use App\Http\Resources\ManagementFund\FormResource;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use App\Policies\ProposalTrfPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TrfCommentController extends Controller
{

    protected $policy;

    public function __construct(ProposalTrfPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function edit(Request $request, Proposal $proposal)
    {
        if (!$this->policy->comment(Auth::user(), $proposal)) abort(403);

        $refBenefits = RefProposalBenefitsItem::query()
            ->orderBy("category", "ASC")
            ->orderBy("order", "ASC")
            ->get();

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $approvement = $proposal->approvement()
            ->with("user")
            ->where("user_id", "!=", Auth::id())
            ->get();

        $myApprovement = $proposal->approvement()
            ->with("user")
            ->where("user_id", Auth::id())
            ->first();

        $filters = $request->session()->get('filters');
        return Inertia::render('ManagementFund/Trf/Comments', [
            "title" => "Edit Proposal | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlSubmit" => route("panel.trf.comment", $proposal),
                "urlIndex" => route("panel.trf.index"),
                "initValue" => (new ShowResource($proposal))->toArray($request),

                "approvement" => $approvement,
                "myApprovement" => $myApprovement
            ]
        ]);
    }

    public function update(CreateComment $createComment, FormCommentRequest $request, Proposal $proposal)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->comment($user, $proposal)) abort(403);

        $arrData = $request->validated();

        $step = $proposal->approvementStep->step ?? 0;

        $roleId = CommentHelper::determineRoleByStep($step);
        $createComment->execute($proposal, $user, $arrData, $roleId);

        if ($request->last) {
            return redirect()->route("panel.trf.index")
                ->with("message", [
                    "status" => "success",
                    "message" => "Comment Proposal '$proposal->project_title' Success!"
                ]);
        }

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Comment Proposal '$proposal->project_title' Success!"
            ]);
    }
}
