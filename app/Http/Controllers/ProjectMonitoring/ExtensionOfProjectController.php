<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\ExtensionProject\CreateExtensionProjectSubmitNotification;
use App\Actions\ProjectMonitoring\ExtensionProject\GetExtensionProjectDatatables;
use App\Actions\ProjectMonitoring\ExtensionProject\UpdateMilestone;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\ExtensionProjectFormRequest;
use App\Http\Requests\ProjectMonitoring\ExtensionProjectSearchRequest;
use App\Http\Resources\ProjectMonitoring\ExtensionProjectTableResource;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Granttchartable;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\User;
use App\Policies\ExtensionProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExtensionOfProjectController extends Controller
{
    protected $policy;

    public function __construct(ExtensionProjectPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(
        GetExtensionProjectDatatables $datatables,
        ExtensionProjectSearchRequest $request
    ) {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ProjectMonitoring/ExtensionOfProject/Index', [
            "title" => "Extension Of Project | Research Project Monitoring",
            "additional" => [
                "data" => ExtensionProjectTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.extension-project.create"),
                "urlIndex" => route("panel.extension-project.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

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

        $application = ExtensionProject::query()
            ->with("granttchart")
            ->where("user_id", $user->id)
            ->where("approval_status", ExtensionProject::STATUS_DRAFT)
            ->first();

        $filters = $request->session()->get('filters');

        return Inertia::render('ProjectMonitoring/ExtensionOfProject/Create', [
            "title" => "Create Extension Of Project | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "projectNumbers" => $projectNumbers,
                "initValue" => $application,
                "urlStore" => route("panel.extension-project.store"),
                "urlIndex" => route("panel.extension-project.index")
            ]
        ]);
    }

    public function store(ExtensionProjectFormRequest $request, UpdateMilestone $updateMilestone, CreateTask $createTask)
    {
        $arrData = $request->validated();
        $proposal = Proposal::find($arrData['proposal_id']);

        if (!$this->policy->create($request->user(), $proposal)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $application = DB::transaction(function () use (
            $arrData,
            $isSubmited,
            $updateMilestone,
            $createTask
        ) {
            $userId = Auth::id();
            $application = ExtensionProject::query()
                ->where("user_id", $userId)
                ->where("approval_status", ExtensionProject::STATUS_DRAFT)
                ->first();

            $arrCreate = [
                'user_id' => $userId,
                'proposal_id' => $arrData['proposal_id'],
                'justification' => $arrData['justification'] ?? '',
                'new_fund' => $arrData['new_fund'] ?? '',
                'duration' => $arrData['duration'] ?? 0,
                'date_end_extension' => $arrData['date_end_extension'] ?? null,
                'balance_to_date' => $arrData['balance_to_date'] ?? null,
                'approval_status' => $isSubmited
                    ? ExtensionProject::STATUS_SUBMITED
                    : ExtensionProject::STATUS_DRAFT,
            ];

            if (!$application) {
                $arrCreate['created_by'] = $userId;
                $application = ExtensionProject::create($arrCreate);
            } else {
                $arrCreate['updated_by'] = $userId;
                $application->update($arrCreate);
            }

            $updateMilestone->execute($application, $arrData);


            if ($isSubmited) {
                $user = User::find(Auth::id());

                $proposal = $application->proposal;
                $createTask->execute(
                    $application,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.extension-project.comment", ["application" => $application->id]),
                    $proposal->application_id,
                    "{$proposal->project_title}",
                    "Extension",
                    Approvement::STATUS_SUBMITED,
                    $proposal->researcher->division
                );

                (new CreateExtensionProjectSubmitNotification)->execute($application, $user);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.extension-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Extension Project Application '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function show(Request $request, ExtensionProject $application)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $application)) abort(403);

        $filters = $request->session()->get('filters');

        $approvement = $application->approvement()
            ->with("user")
            ->get();

        $proposal = $application->proposal;

        $extensionProjects = $proposal->extensionProject()
            ->with("granttchart")
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->where("id", "<", $application->id)
            ->get();

        return Inertia::render('ProjectMonitoring/ExtensionOfProject/Show', [
            "title" => "View Extension Of Project | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $application->load(["proposal.milestones" => function ($query) {
                    return $query->where("type", ProposalMilestone::TYPE_PROPOSAL);
                }, "proposal.researcher", "granttchart"]),
                "otherMilestones" => $extensionProjects,
                "approvement" => $approvement,
                "canEdit" => $this->policy->update($user, $application),
                "urlEdit" => route("panel.extension-project.edit", ["application" => $application->id]),
                "urlIndex" => route("panel.extension-project.index")
            ]
        ]);
    }

    public function edit(Request $request, ExtensionProject $application)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $application)) abort(403);

        $projectNumbers = Proposal::query()
            ->where("id", $application->proposal_id)
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $filters = $request->session()->get('filters');

        return Inertia::render('ProjectMonitoring/ExtensionOfProject/Edit', [
            "title" => "Edit Extension Of Project | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "projectNumbers" => $projectNumbers,
                "initValue" => $application->load("granttchart"),
                "urlUpdate" => route("panel.extension-project.update", ["application" => $application->id]),
                "urlIndex" => route("panel.extension-project.index")
            ]
        ]);
    }

    public function update(ExtensionProjectFormRequest $request, UpdateMilestone $updateMilestone, ExtensionProject $application, CreateTask $createTask)
    {
        $arrData = $request->validated();
        if (!$this->policy->update($request->user(), $application)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $proposal = $application->proposal;

        $application = DB::transaction(function () use ($arrData, $isSubmited, $application, $updateMilestone, $createTask) {
            $userId = Auth::id();

            $application->update([
                'user_id' => $userId,
                'proposal_id' => $arrData['proposal_id'],
                'justification' => $arrData['justification'] ?? '',
                'new_fund' => $arrData['new_fund'] ?? '',
                'duration' => $arrData['duration'] ?? 0,
                'date_end_extension' => $arrData['date_end_extension'] ?? null,
                'balance_to_date' => $arrData['balance_to_date'] ?? null,
                'approval_status' => $isSubmited
                    ? ExtensionProject::STATUS_RE_SUBMIT
                    : $application->approval_status,
                'updated_by' => $userId
            ]);

            $updateMilestone->execute($application, $arrData);


            if ($isSubmited) {
                $user = User::find(Auth::id());

                $proposal = $application->proposal;
                $createTask->execute(
                    $application,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.extension-project.comment", ["application" => $application->id]),
                    $proposal->application_id,
                    "{$proposal->project_title}",
                    "Extension",
                    Approvement::STATUS_SUBMITED,
                    $proposal->researcher->division
                );

                (new CreateExtensionProjectSubmitNotification)->execute($application, $user);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.extension-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Extension Project Application '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function destroy(ExtensionProject $application)
    {
        $user = User::find(Auth::id());

        if (!$this->policy->delete($user, $application)) abort(403);

        $reportName = $application->proposal->project_title;
        DB::transaction(function () use ($application, $user) {
            $application->deleted_by = $user->id;
            $application->save();
            $application->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Extension Project Application '$reportName' Success!"
            ]);
    }
}
