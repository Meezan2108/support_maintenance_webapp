<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\CreateReport;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\CreateReportObjectives;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\CreateReportProjectTeam;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\CreateResearchProgressNoFundSubmitNotification;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\GetResearchProgressNoFundDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\ResearchProgressNoFundFormRequest;
use App\Http\Requests\ProjectMonitoring\ResearchProgressSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\ProjectMonitoring\ResearchProgressNoFundTableResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefReportType;
use App\Models\ReportResearchProgress;
use App\Models\User;
use App\Policies\ResearchProgressNoFundPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResearchProgressNoFundController extends Controller
{
    protected $policy;

    public function __construct(ResearchProgressNoFundPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(
        GetResearchProgressNoFundDatatables $datatables,
        ResearchProgressSearchRequest $request
    ) {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ProjectMonitoring/ResearchProgressNoFund/Index', [
            "title" => "Resarch Progress Report No Fund | Research Project Monitoring",
            "additional" => [
                "data" => ResearchProgressNoFundTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.research-progress-no-fund.create"),
                "urlIndex" => route("panel.research-progress-no-fund.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

        $projectTitles = Proposal::query()
            ->where("user_id", $user->id)
            ->whereIn("project_status", [Proposal::STATUS_PRJ_ON_GOING, Proposal::STATUS_PRJ_EXTENDED])
            ->get(["id", "project_title"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_title
                ];
            });

        $reportTypes = RefReportType::all();

        $report = ReportResearchProgress::query()
            ->with(['projectTeam', 'objective'])
            ->where("user_id", $user->id)
            ->where("approval_status", ReportResearchProgress::STATUS_DRAFT)
            ->whereNull("proposal_id")
            ->first();

        if ($report) {
            $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('ProjectMonitoring/ResearchProgressNoFund/Create', [
            "title" => "Create Research Progress No Fund | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "projectTitles" => $projectTitles,
                "reportTypes" => $reportTypes,
                "initValue" => $report,
                "urlStore" => route("panel.research-progress-no-fund.store"),
                "urlIndex" => route("panel.research-progress-no-fund.index")
            ]
        ]);
    }

    public function store(ResearchProgressNoFundFormRequest $request)
    {
        $arrData = $request->validated();
        $user = $request->user();
        if (!$this->policy->create($user)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use (
            $arrData,
            $user,
            $request,
            $isSubmited
        ) {

            $arrData["new_files"] = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            $arrData["approval_status"] = $isSubmited
                ? Approvement::STATUS_SUBMITED
                : Approvement::STATUS_DRAFT;

            $report = ReportResearchProgress::query()
                ->where("user_id", $user->id)
                ->where("approval_status", ReportResearchProgress::STATUS_DRAFT)
                ->whereNull("proposal_id")
                ->first();

            $report = (new CreateReport)->execute($report, $user, $arrData);

            (new CreateReportProjectTeam)->execute($report, $arrData["project_team"] ?? []);
            (new CreateReportObjectives)->execute($report, $arrData["objectives"] ?? []);

            if ($isSubmited) {
                (new CreateTask)->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.research-progress-no-fund.comment", ["report" => $report->id]),
                    " - ",
                    "{$report->project_title} ({$report->year} - {$report->reportType->description})",
                    "Research Progress No Fund",
                    Approvement::STATUS_SUBMITED,
                    $report->division
                );

                (new CreateResearchProgressNoFundSubmitNotification)->execute($report, $user);
            }

            return $report;
        });

        if ($isSubmited) {
            return redirect()->route('panel.research-progress-no-fund.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Research Progress Report '{$report->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function show(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $report)) abort(403);

        $filters = $request->session()->get('filters');

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        $report = $report->load(
            "reportType",
            "pslkm",
            "projectTeam",
            "objective",
            "fileable"
        );

        $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));

        return Inertia::render('ProjectMonitoring/ResearchProgressNoFund/Show', [
            "title" => "View Research Progress No Fund | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $report,
                "approvement" => $approvement,
                "canEdit" => $this->policy->update($user, $report),
                "urlEdit" => route("panel.research-progress-no-fund.edit", ["report" => $report->id]),
                "urlIndex" => route("panel.research-progress-no-fund.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $report)) abort(403);

        $reportTypes = RefReportType::all();

        $filters = $request->session()->get('filters');

        $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));

        return Inertia::render('ProjectMonitoring/ResearchProgressNoFund/Edit', [
            "title" => "Edit Research Progress No Fund | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "reportTypes" => $reportTypes,
                "initValue" => $report->load(['projectTeam', 'objective']),
                "urlSubmit" => route("panel.research-progress-no-fund.update", ["report" => $report->id]),
                "urlIndex" => route("panel.research-progress-no-fund.index")
            ]
        ]);
    }

    public function update(
        ResearchProgressNoFundFormRequest $request,
        ReportResearchProgress $report,
        CreateTask $createTask
    ) {
        $arrData = $request->validated();
        $user = $request->user();
        if (!$this->policy->update($user, $report)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($arrData, $isSubmited, $report, $createTask, $request, $user) {
            $arrData["new_files"] = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            $arrData["approval_status"] = $isSubmited
                ? Approvement::STATUS_RE_SUBMIT
                : $report->approval_status;

            $report = (new CreateReport)->execute($report, $user, $arrData);

            (new CreateReportProjectTeam)->execute($report, $arrData["project_team"] ?? []);
            (new CreateReportObjectives)->execute($report, $arrData["objectives"] ?? []);

            if ($isSubmited) {
                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.research-progress-no-fund.comment", ["report" => $report->id]),
                    ' - ',
                    "{$report->project_title} ({$report->year} - {$report->reportType->description})",
                    "Research Progress No Fund",
                    Approvement::STATUS_RE_SUBMIT,
                    $report->division
                );

                (new CreateResearchProgressNoFundSubmitNotification)->execute($report, $user);
            }

            return $report;
        });

        if ($isSubmited) {
            return redirect()->route('panel.research-progress-no-fund.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Edit Research Progress Report No Fund '{$report->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function destroy(ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->delete($user, $report)) abort(403);

        $reportName = $report->project_title;
        DB::transaction(function () use ($report, $user) {
            $report->deleted_by = $user->id;
            $report->save();
            $report->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Report Research Progress No Fund '$reportName' Success!"
            ]);
    }
}
