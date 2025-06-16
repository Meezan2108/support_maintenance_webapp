<?php

namespace App\Http\Controllers\ApplicationManagement;

use App\Actions\ApplicationManagement\ListOfProposalDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\ListOfProposalSearchRequest;
use App\Http\Resources\ApplicationManagement\FormResource;
use App\Http\Resources\ApplicationManagement\ListOfProposalTableResource;
use App\Http\Resources\ApplicationManagement\ShowResource;
use App\Http\Resources\ManagementFund\FormResource as ManagementFundFormResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ListOfApprovedController extends Controller
{

    protected $policy;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(ListOfProposalDatatables $datatables, ListOfProposalSearchRequest $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters, Proposal::STATUS_APPROVED);

        $request->session()->put('filters', $filters);

        return Inertia::render('ApplicationManagement/ListOfApproved/Index', [
            "title" => "List of Approved Project | Application Management",
            "additional" => [
                "data" => ListOfProposalTableResource::collection($data),
                "canCreate" => $this->policy->create($user),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "urlIndex" => route("panel.list-of-approved.index"),
                "urlCreate" => "#"
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->policy->create(Auth::user())) abort(403);

        $proposalType = $request->proposal_type == "trf"
            ? 1 : 2;

        $proposalDraft = Proposal::where('approval_status', Approvement::STATUS_TEMP)
            ->where("proposal_type", $proposalType)
            ->rmcProposal(Auth::id())
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
        return Inertia::render('ApplicationManagement/ListOfApproved/Create', [
            "title" => "Create Proposal | Management of Fund & Research Grants",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refBenefits" => $refBenefits,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlIndex" => route("panel.list-of-approved.index"),
                "initValue" => $proposalDraft
                    ? (new ManagementFundFormResource($proposalDraft))->toArray($request)
                    : null,
                "proposalType" => $proposalType
            ]
        ]);
    }

    public function show(Request $request, Proposal $proposal)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $proposal)) abort(403);

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $filters = $request->session()->get('filters');
        return Inertia::render('ApplicationManagement/ListOfApproved/Show', [
            "title" => "View Proposal | Application Management",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "urlIndex" => route("panel.list-of-approved.index"),
                "initValue" => (new ShowResource($proposal))->toArray($request),
            ]
        ]);
    }

    public function edit(Request $request, Proposal $proposal)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->revision($user, $proposal)) abort(403);

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $filters = $request->session()->get('filters');
        return Inertia::render('ApplicationManagement/ListOfApproved/Edit', [
            "title" => "Edit Proposal | Application Management",
            "additional" => [
                "filters" => $filters,
                "activeTab" => $request->active_tab,
                "refProjectCostSeriesDirect" => $refProjectCostSeriesDirect,
                "arrProjectStatus" => array_values(collect(Proposal::ARR_STATUS_PROJECT)->map(function ($item, $key) {
                    return [
                        "id" => $key,
                        "description" => $item
                    ];
                })->toArray()),
                "urlBase" => route("panel.list-of-approved.edit", $proposal),
                // "urlStore" => route("panel.list-of-approved.update", $proposal),
                "urlIndex" => route("panel.list-of-approved.index"),
                "initValue" => (new FormResource($proposal))->toArray($request),
            ]
        ]);
    }
}
