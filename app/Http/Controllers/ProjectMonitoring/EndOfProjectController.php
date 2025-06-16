<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\ProjectMonitoring\EndOfProject\GetEndOfProjectDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProjectSearchRequest;
use App\Http\Resources\ProjectMonitoring\EndOfProjectFormResource;
use App\Http\Resources\ProjectMonitoring\EndOfProjectShowResource;
use App\Http\Resources\ProjectMonitoring\EndOfProjectTableResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefReportEopBenefitsGroup;
use App\Models\RefReportEopBenefitsQuestion;
use App\Models\RefReportEopBenefitsSection;
use App\Models\ReportEndProject;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EndOfProjectController extends Controller
{
    protected $policy;
    protected $arrCreateLink;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(
        GetEndOfProjectDatatables $datatables,
        EndOfProjectSearchRequest $request
    ) {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ProjectMonitoring/EndOfProject/Index', [
            "title" => "End Of Project | Project Monitoring",
            "additional" => [
                "data" => EndOfProjectTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.end-of-project.create"),
                "urlIndex" => route("panel.end-of-project.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("user_id", $user->id)
            ->whereIn("project_status", [Proposal::STATUS_PRJ_ON_GOING, Proposal::STATUS_PRJ_EXTENDED])
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $report = ReportEndProject::with("benefitAnswer")
            ->where('user_id', $user->id)
            ->where('approval_status', Approvement::STATUS_DRAFT)
            ->first();

        $questionsBenefit = RefReportEopBenefitsGroup::with(
            "section.question"
        )->get();

        return Inertia::render('ProjectMonitoring/EndOfProject/Create', [
            "title" => "Create Report | Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new EndOfProjectFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "questionsBenefit" => $questionsBenefit,
                "urlIndex" => route("panel.end-of-project.index")
            ]
        ]);
    }

    public function show(Request $request, ReportEndProject $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $report)) abort(403);

        $filters = $request->session()->get('filters');

        $report = $report->load(
            "proposal.researcher",
            "proposal.typeOfFund",
            "proposal.teams",

            "proposal.researchType",
            "proposal.researchCluster",
            "proposal.seoCategory",
            "proposal.seoGroup",
            "proposal.seoArea",
            "proposal.primaryResearchField.category",
            "proposal.primaryResearchField.group",
            "proposal.primaryResearchField.area",
            "proposal.secondaryResearchField.category",
            "proposal.secondaryResearchField.group",
            "proposal.secondaryResearchField.area",

            "benefitAnswer"
        );

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        $questionsBenefit = RefReportEopBenefitsGroup::with(
            "section.question"
        )->get();


        return Inertia::render('ProjectMonitoring/EndOfProject/Show', [
            "title" => "View Report | Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new EndOfProjectShowResource($report))->toArray($request) : null,
                "approvement" => $approvement,
                "questionsBenefit" => $questionsBenefit,
                "urlIndex" => route("panel.monitoring-trf.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportEndProject $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("user_id", $user->id)
            ->where("id", $report->proposal_id)
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $report = $report->load("benefitAnswer");

        $questionsBenefit = RefReportEopBenefitsGroup::with(
            "section.question"
        )->get();

        return Inertia::render('ProjectMonitoring/EndOfProject/Edit', [
            "title" => "Edit Report | Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new EndOfProjectFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "questionsBenefit" => $questionsBenefit,
                "urlIndex" => route("panel.end-of-project.index")
            ]
        ]);
    }

    public function destroy(ReportEndProject $report)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->delete($user, $report)) abort(403);

        $reportName = "{$report->proposal->project_title}: {$report->year} - Quarter {$report->quarter}";
        DB::transaction(function () use ($report, $user) {
            $report->deleted_by = $user->id;
            $report->save();
            $report->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Report '$reportName' Success!"
            ]);
    }
}
