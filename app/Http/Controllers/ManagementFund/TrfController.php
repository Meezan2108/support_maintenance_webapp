<?php

namespace App\Http\Controllers\ManagementFund;

use App\Actions\ManagementFund\Trf\GetTrfDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\TrfSearchRequest;
use App\Http\Resources\ManagementFund\FormResource;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Http\Resources\ManagementFund\TrfTableResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class TrfController extends Controller
{

    protected $policy;

    public function __construct(ProposalTrfPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetTrfDatatables $trfDatatables, TrfSearchRequest $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $trfDatatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ManagementFund/Trf/Index', [
            "title" => "TRF | Management of Fund & Research Grants",
            "additional" => [
                "data" => TrfTableResource::collection($data),
                "filters" => $filters,
                "columns" => $trfDatatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.trf.create"),
                "urlIndex" => route("panel.trf.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->policy->create(Auth::user())) abort(403);

        $proposalDraft = Proposal::where('approval_status', Approvement::STATUS_TEMP)
            ->where("proposal_type", Proposal::TYPE_TRF)
            ->where("user_id", Auth::id())
            ->first();

        $refBenefits = RefProposalBenefitsItem::query()
            ->orderBy("category", "ASC")
            ->orderBy("order", "ASC")
            ->get();

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $filters = $request->session()->get('filters');
        return Inertia::render('ManagementFund/Trf/Create', [
            "title" => "Create Proposal TRF | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.trf.create"),
                "urlIndex" => route("panel.trf.index"),
                "initValue" => $proposalDraft ? (new FormResource($proposalDraft))->toArray($request) : null
            ]
        ]);
    }

    public function show(Request $request, Proposal $proposal)
    {
        if (!$this->policy->view(Auth::user(), $proposal)) abort(403);

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
            ->get();

        $filters = $request->session()->get('filters');
        return Inertia::render('ManagementFund/Trf/Show', [
            "title" => "View Proposal TRF | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.trf.create"),
                "urlIndex" => route("panel.trf.index"),
                "initValue" => (new ShowResource($proposal))->toArray($request),

                "approvement" => $approvement,
            ]
        ]);
    }

    public function edit(Request $request, Proposal $proposal)
    {
        if (
            !$this->policy->update(Auth::user(), $proposal)
            && $proposal->is_by_rmc == 0
        ) {
            abort(403);
        }


        $rmcPolicy = new ListOfApprovedPolicy();
        if (
            !$rmcPolicy->create(Auth::user(), $proposal)
            && $proposal->is_by_rmc
        ) {
            abort(403);
        }

        $refBenefits = RefProposalBenefitsItem::query()
            ->orderBy("category", "ASC")
            ->orderBy("order", "ASC")
            ->get();

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $filters = $request->session()->get('filters');
        return Inertia::render('ManagementFund/Trf/Edit', [
            "title" => "Edit Proposal TRF | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.trf.edit", $proposal),
                "urlIndex" => route("panel.trf.index"),
                "initValue" => (new FormResource($proposal))->toArray($request),
            ]
        ]);
    }

    public function destroy(Proposal $proposal)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->delete($user, $proposal)) abort(403);

        DB::transaction(function () use ($proposal, $user) {
            $proposal->deleted_by = $user->id;
            $proposal->save();
            $proposal->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Proposal '$proposal->project_title' Success!"
            ]);
    }
}
