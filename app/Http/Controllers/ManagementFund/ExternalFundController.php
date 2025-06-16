<?php

namespace App\Http\Controllers\ManagementFund;

use App\Actions\ManagementFund\ExternalFund\GetExternalFundDatatables;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\ExternalFundSearchRequest;
use App\Http\Resources\ManagementFund\ExternalFundTableResource;
use App\Http\Resources\ManagementFund\FormResource;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalExternalFundPolicy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExternalFundController extends Controller
{

    protected $policy;

    public function __construct(ProposalExternalFundPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetExternalFundDatatables $datatables, ExternalFundSearchRequest $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ManagementFund/ExternalFund/Index', [
            "title" => "External Fund | Management of Fund & Research Grants",
            "additional" => [
                "data" => ExternalFundTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.external-fund.create"),
                "urlIndex" => route("panel.external-fund.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->policy->create(Auth::user())) abort(403);

        $proposalDraft = Proposal::where('approval_status', Approvement::STATUS_TEMP)
            ->where("proposal_type", Proposal::TYPE_EXTERNAL_FUND)
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
        return Inertia::render('ManagementFund/ExternalFund/Create', [
            "title" => "Create Proposal External Fund | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.external-fund.create"),
                "urlIndex" => route("panel.external-fund.index"),
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
        return Inertia::render('ManagementFund/ExternalFund/Show', [
            "title" => "View Proposal External Fund | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.external-fund.create"),
                "urlIndex" => route("panel.external-fund.index"),
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
        return Inertia::render('ManagementFund/ExternalFund/Edit', [
            "title" => "Edit Proposal External Fund | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlBase" => route("panel.external-fund.edit", $proposal),
                "urlIndex" => route("panel.external-fund.index"),
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
