<?php

namespace App\Http\Controllers\ApplicationManagement;

use App\Actions\ApplicationManagement\TechnicalEvaluation\CreateEvaluation;
use App\Actions\ApplicationManagement\TechnicalEvaluation\CreateEvaluationNotification;
use App\Actions\ApplicationManagement\TechnicalEvaluation\GetEvaluationDatatables;
use App\Actions\ApplicationManagement\TechnicalEvaluation\UpdateProposalApprovement;
use App\Actions\CreateTask;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\EvaluationFormRequest;
use App\Http\Requests\ApplicationManagement\EvaluationSearchRequest;
use App\Http\Resources\ApplicationManagement\EvaluationTableResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefEvaluationQuestion;
use App\Models\User;
use App\Policies\EvaluationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EvaluationController extends Controller
{
    protected $policy;

    public function __construct(EvaluationPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetEvaluationDatatables $datatables, EvaluationSearchRequest $request)
    {
        if (!$this->policy->viewAny(Auth::user())) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('ApplicationManagement/TechnicalEvaluation/Index', [
            "title" => "Technical Evaluation | Application Management",
            "additional" => [
                "data" => EvaluationTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "urlIndex" => route("panel.technical-evaluation.index")
            ]
        ]);
    }

    /**
     *
     * algorithm restrict by role..
     *
     * mapping user berdasarkan role
     * filter role apa aja yang bisa tampil
     * order asc
     * kirim ke tampilannya
     *
     */

    public function show(Request $request, Proposal $proposal)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $proposal)) abort(403);

        $refQuestion = RefEvaluationQuestion::all();

        $evaluation = $proposal->evaluation()
            ->with("answer", "evaluator.position", "evaluator.division")
            ->get();

        if ($user->hasRole(["Super Admin"])) {
            $topRoleId = User::ROLE_LKM_DIRECTOR;
        } else {
            $topRoleId = CommentHelper::getTopUserRole($user, $evaluation);
        }

        $arrStep = collect(EvaluationPolicy::ARR_STEP);

        $currentStep = $arrStep->firstWhere("role_id", $topRoleId);

        $arrStep = $arrStep->filter(function ($item) use ($currentStep) {
            return in_array($item["role_id"], $currentStep["related_role"] ?? []);
        })->map(function ($item) use ($evaluation) {
            $selEvaluation = $evaluation->firstWhere("role_id", $item["role_id"]);
            $item["evaluation"] = $selEvaluation;
            $item["approval_status"] = Approvement::ARR_STATUS[optional($selEvaluation)->approval_status] ?? ' - ';

            return $item;
        });

        $filters = $request->session()->get('filters');
        return Inertia::render('ApplicationManagement/TechnicalEvaluation/Show', [
            "title" => "Clients | Application Management",
            "additional" => [
                "filters" => $filters,
                "proposal" => $proposal->load("researcher"),
                "questions" => $refQuestion->toArray(),
                "questionSummary" => array_values($refQuestion->where("category", 1)->toArray()),
                "questionProposal" => array_values($refQuestion->where("category", 2)->toArray()),
                "questionRisk" => array_values($refQuestion->where("category", 3)->toArray()),
                "initActiveTab" => $request->active_tab ?? $currentStep["code"] ?? null,
                "arrStep" => $arrStep,
                "dateNow" => date("Y-m-d"),
                "urlBase" => route("panel.technical-evaluation.show", $proposal),
                "urlIndex" => route("panel.technical-evaluation.index")
            ]
        ]);
    }

    public function edit(Request $request, Proposal $proposal)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $proposal)) {
            return redirect()->route("panel.technical-evaluation.show", ["proposal" => $proposal->id]);
        }

        $refQuestion = RefEvaluationQuestion::all();

        $approvementStep = $proposal->approvementStep;
        $evaluation = $proposal->evaluation()
            ->with("answer", "evaluator.position", "evaluator.division")
            ->get();

        $arrStep = collect(EvaluationPolicy::ARR_STEP);

        $roleId = CommentHelper::determineRoleByStep($approvementStep->step ?? 0);
        $currentStep = $arrStep->firstWhere("role_id", $roleId);

        $arrStep = $arrStep->filter(function ($item) use ($currentStep) {
            return in_array($item["role_id"], $currentStep["related_role"] ?? []);
        })->map(function ($item) use ($evaluation) {
            $selEvaluation = $evaluation->firstWhere("role_id", $item["role_id"]);
            $item["evaluation"] = $selEvaluation;
            $item["approval_status"] = Approvement::ARR_STATUS[optional($selEvaluation)->approval_status] ?? ' - ';

            return $item;
        });


        $filters = $request->session()->get('filters');
        return Inertia::render('ApplicationManagement/TechnicalEvaluation/Edit', [
            "title" => "Edit Technical Evaluation | Application Management",
            "additional" => [
                "filters" => $filters,
                "proposal" => $proposal->load("researcher"),
                "questions" => $refQuestion->toArray(),
                "questionSummary" => array_values($refQuestion->where("category", 1)->toArray()),
                "questionProposal" => array_values($refQuestion->where("category", 2)->toArray()),
                "questionRisk" => array_values($refQuestion->where("category", 3)->toArray()),
                "user" => $user->load("position", "division"),
                "initActiveTab" => $request->active_tab ?? $currentStep["code"],
                "currentStep" => $currentStep,
                "arrStep" => $arrStep,
                "dateNow" => date("Y-m-d"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
                "urlBase" => route("panel.technical-evaluation.edit", $proposal),
                "urlUpdate" => route("panel.technical-evaluation.update", $proposal),
                "urlIndex" => route("panel.technical-evaluation.index")
            ]
        ]);
    }

    public function update(
        EvaluationFormRequest $request,
        Proposal $proposal,
        CreateEvaluation $createEvaluation,
        UpdateApprovementStep $updateApprovementStep,
        UpdateProposalApprovement $updateProposalApprovement,
        UpdateApprovement $updateApprovement
    ) {

        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $proposal)) {
            return redirect()->route("panel.technical-evaluation.show", ["proposal" => $proposal->id]);
        }

        $evaluation = DB::transaction(function () use (
            $request,
            $proposal,
            $createEvaluation,
            $updateApprovementStep,
            $updateApprovement,
            $updateProposalApprovement,
            $user
        ) {
            $arrData = $request->all();

            $approvementStep = $proposal->approvementStep;
            $currentStep = $approvementStep->step ?? 0;
            $evaluation = $createEvaluation->execute($proposal, $user, $arrData, $currentStep);
            $approvement = $updateProposalApprovement->execute($proposal, $user, $arrData, $currentStep);
            $approvementStep = $updateApprovementStep->execute($proposal, $user);

            $arrData["status"] = $evaluation->approval_status;

            $updateApprovement->execute($proposal, $approvement, $approvementStep, $arrData);

            if ($proposal->approval_status == Proposal::STATUS_APPROVED) {
                $proposal->project_status = Proposal::STATUS_PRJ_PROPOSAL;
                $proposal->save();
                (new CreateTask)->execute(
                    $proposal,
                    $user,
                    User::ROLE_RMC,
                    "group",
                    route("panel.list-of-approved.edit", ["proposal" => $proposal->id]),
                    $proposal->application_id,
                    $proposal->project_title,
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? "TRF"
                        : "External Fund",
                    $proposal->approval_status
                );
            } else if ($proposal->approval_status ==  Proposal::STATUS_REJECTED) {
                $proposal->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTaskByStatus)->execute(
                    $approvement,
                    $approvementStep,
                    route("panel.technical-evaluation.edit", ["proposal" => $proposal->id]),
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? route("panel.trf.edit", ["proposal" => $proposal->id])
                        : route("panel.external-fund.edit", ["proposal" => $proposal->id]),
                    $proposal->application_id,
                    $proposal->project_title,
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? "TRF"
                        : "External Fund"
                );
            }
            $proposal = $proposal->load("approvementStep");
            (new CreateEvaluationNotification)->execute($proposal, $user, $approvement->status);

            return $evaluation;
        });

        return redirect()->route("panel.technical-evaluation.index")
            ->with("message", [
                "status" => "success",
                "message" => "Submit Technical Evaluation For Proposal: '$proposal->project_title' Success!"
            ]);
    }
}
