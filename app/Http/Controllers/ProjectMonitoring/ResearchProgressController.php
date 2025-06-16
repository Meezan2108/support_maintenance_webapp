<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\ResearchProgress\CreateResearchProgressSubmitNotification;
use App\Actions\ProjectMonitoring\ResearchProgress\GetResearchProgressDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\ResearchProgressFormRequest;
use App\Http\Requests\ProjectMonitoring\ResearchProgressSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\ProjectMonitoring\ResearchProgressTableResource;
use App\Models\Approvement;
use App\Models\Fileable;
use App\Models\Proposal;
use App\Models\RefReportType;
use App\Models\ReportResearchProgress;
use App\Models\User;
use App\Policies\ResearchProgressPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ResearchProgressController extends Controller
{
    protected $policy;

    public function __construct(ResearchProgressPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(
        GetResearchProgressDatatables $datatables,
        ResearchProgressSearchRequest $request
    ) {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) {
            abort(403);
        }

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ProjectMonitoring/ResearchProgress/Index', [
            "title" => "Resarch Progress Report | Research Project Monitoring",
            "additional" => [
                "data" => ResearchProgressTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.research-progress.create"),
                "urlIndex" => route("panel.research-progress.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) {
            abort(403);
        }

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
            ->where("user_id", $user->id)
            ->where("approval_status", ReportResearchProgress::STATUS_DRAFT)
            ->whereNotNull("proposal_id")
            ->first();

        if ($report) {
            $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));
        }


        $filters = $request->session()->get('filters');

        return Inertia::render('ProjectMonitoring/ResearchProgress/Create', [
            "title" => "Create Research Progress | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "projectTitles" => $projectTitles,
                "reportTypes" => $reportTypes,
                "initValue" => $report,
                "urlStore" => route("panel.research-progress.store"),
                "urlIndex" => route("panel.research-progress.index")
            ]
        ]);
    }

    public function store(ResearchProgressFormRequest $request, CreateTask $createTask)
    {
        $arrData = $request->validated();
        $proposal = Proposal::find($arrData['proposal_id']);

        if (!$this->policy->create($request->user(), $proposal)) {
            abort(403);
        }

        $isSubmited = $arrData["is_submited"] ?? false;

        DB::transaction(function () use ($arrData, $isSubmited, $createTask, $request, $proposal) {
            $userId = Auth::id();
            $report = ReportResearchProgress::query()
                ->where("user_id", $userId)
                ->where("approval_status", ReportResearchProgress::STATUS_DRAFT)
                ->whereNotNull("proposal_id")
                ->first();

            $arrCreate = [
                'user_id' => $userId,
                'ref_division_id' => optional($proposal->researcher)->division_id,

                'proposal_id' => $arrData['proposal_id'],
                'ref_report_type_id' => $arrData['ref_report_type_id'] ?? 0,
                'ref_pslkm_id' => $arrData['ref_pslkm_id'] ?? 0,
                'ref_pslkm_sub_id' => $arrData['ref_pslkm_sub_id'] ?? 0,

                'year' => $arrData['year'] ?? '',
                'focus_area' => $arrData['focus_area'] ?? '',
                'issue' => $arrData['issue'] ?? '',
                'strategy' => $arrData['strategy'] ?? '',
                'program' => $arrData['program'] ?? '',
                'date' => $arrData['date'] ?? null,
                'background' => $arrData['background'] ?? '',
                'result' => $arrData['result'] ?? '',
                'summary' => $arrData['summary'] ?? '',

                'approval_status' => $isSubmited
                    ? ReportResearchProgress::STATUS_SUBMITED
                    : ReportResearchProgress::STATUS_DRAFT,
            ];

            if (!$report) {
                $arrCreate['created_by'] = $userId;
                $report = ReportResearchProgress::create($arrCreate);
            } else {
                $arrCreate['updated_by'] = $userId;
                $report->update($arrCreate);
            }


            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $report->fileable()->create(array_merge([
                        "code_type" => ReportResearchProgress::FILEABLE_DOC_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
            $report->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            if ($isSubmited) {
                $user = User::find(Auth::id());

                $proposal = $report->proposal;
                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.research-progress.comment", ["report" => $report->id]),
                    $proposal->application_id,
                    "{$report->proposal->project_title} ({$report->year} - {$report->reportType->description})",
                    "Research Progress",
                    Approvement::STATUS_SUBMITED,
                    $proposal->researcher->division
                );

                (new CreateResearchProgressSubmitNotification)->execute($report, $user);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.research-progress.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Research Progress Report '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function show(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $report)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        $report = $report->load(
            "reportType",
            "pslkm",
            "proposal.researcher",
            "proposal.teams",
            "proposal.objectives",
            "proposal.typeOfFund",
            "fileable"
        );

        $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));

        return Inertia::render('ProjectMonitoring/ResearchProgress/Show', [
            "title" => "View Research Progress | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $report,
                "approvement" => $approvement,
                "canEdit" => $this->policy->update($user, $report),
                "urlEdit" => route("panel.research-progress.edit", ["report" => $report->id]),
                "urlIndex" => route("panel.research-progress.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $report)) {
            abort(403);
        }

        $projectTitles = Proposal::query()
            ->where("user_id", $user->id)
            ->whereIn("project_status", [Proposal::STATUS_PRJ_ON_GOING, Proposal::STATUS_PRJ_EXTENDED])
            ->where("id", $report->proposal_id)
            ->get(["id", "project_title"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_title
                ];
            });

        $reportTypes = RefReportType::all();

        $filters = $request->session()->get('filters');

        $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));

        return Inertia::render('ProjectMonitoring/ResearchProgress/Edit', [
            "title" => "Edit Research Progress | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "projectTitles" => $projectTitles,
                "reportTypes" => $reportTypes,
                "initValue" => $report,
                "urlSubmit" => route("panel.research-progress.update", ["report" => $report->id]),
                "urlIndex" => route("panel.research-progress.index")
            ]
        ]);
    }

    public function update(ResearchProgressFormRequest $request, ReportResearchProgress $report, CreateTask $createTask)
    {
        $arrData = $request->validated();
        if (!$this->policy->update($request->user(), $report)) {
            abort(403);
        }

        $isSubmited = $arrData["is_submited"] ?? false;

        $proposal = $report->proposal;
        $report = DB::transaction(function () use ($arrData, $isSubmited, $report, $createTask, $proposal, $request) {
            $userId = Auth::id();

            $arrUpdate = [
                'user_id' => $userId,
                'ref_division_id' => optional($proposal->researcher)->division_id,
                'proposal_id' => $arrData['proposal_id'],
                'ref_report_type_id' => $arrData['ref_report_type_id'] ?? 0,
                'ref_pslkm_id' => $arrData['ref_pslkm_id'] ?? 0,
                'ref_pslkm_sub_id' => $arrData['ref_pslkm_sub_id'] ?? 0,
                'year' => $arrData['year'] ?? '',
                'focus_area' => $arrData['focus_area'] ?? '',
                'issue' => $arrData['issue'] ?? '',
                'strategy' => $arrData['strategy'] ?? '',
                'program' => $arrData['program'] ?? '',
                'date' => $arrData['date'] ?? null,
                'background' => $arrData['background'] ?? '',
                'result' => $arrData['result'] ?? '',
                'summary' => $arrData['summary'] ?? '',

                'approval_status' => $isSubmited
                    ? Approvement::STATUS_RE_SUBMIT
                    : $report->approval_status,
            ];

            $arrUpdate['updated_by'] = $userId;
            $report->update($arrUpdate);


            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $report->fileable()->create(array_merge([
                        "code_type" => ReportResearchProgress::FILEABLE_DOC_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
            $report->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            if ($isSubmited) {
                $user = User::find(Auth::id());

                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.research-progress.comment", ["report" => $report->id]),
                    $proposal->application_id,
                    "{$report->proposal->project_title} ({$report->year} - {$report->reportType->description})",
                    "Research Progress",
                    Approvement::STATUS_RE_SUBMIT,
                    $proposal->researcher->division
                );

                (new CreateResearchProgressSubmitNotification)->execute($report, $user);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.research-progress.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Edit Research Progress Report '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function destroy(ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->delete($user, $report)) {
            abort(403);
        }

        $reportName = $report->proposal->project_title;
        DB::transaction(function () use ($report, $user) {
            $report->deleted_by = $user->id;
            $report->save();
            $report->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Report Research Progress '$reportName' Success!"
            ]);
    }
}
