<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateComment;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\ProjectMonitoring\EndOfProject\CreateEndProjectCommentNotification;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProjectCommentRequest;
use App\Http\Resources\ProjectMonitoring\EndOfProjectShowResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EndOfProjectCommentController extends Controller
{
    protected $policy;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function edit(Request $request, ReportEndProject $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->comment($user, $report)) abort(403);

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
        );

        $approvement = $report->approvement()
            ->with("user")
            ->where("user_id", "!=", Auth::id())
            ->get();

        $myApprovement = $report->approvement()
            ->with("user")
            ->where("user_id", Auth::id())
            ->first();

        return Inertia::render('ProjectMonitoring/EndOfProject/Comment', [
            "title" => "Comment/Approval End Of Project | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => (new EndOfProjectShowResource($report))->toArray($request),
                "approvement" => $approvement,
                "myApprovement" => $myApprovement,
                "optionsStatus" => CommentHelper::getOptionsStatus(),
                "urlIndex" => route("panel.end-of-project.index"),
                "urlSubmit" => route("panel.end-of-project.comment", ["report" => $report->id])
            ]
        ]);
    }

    public function update(
        EndOfProjectCommentRequest $request,
        ReportEndProject $report,
        CreateComment $createComment,
        UpdateApprovement $updateApprovement,
        UpdateApprovementStep $updateApprovementStep
    ) {
        if (!$this->policy->comment($request->user(), $report)) abort(403);

        $arrData = $request->validated();
        $isSubmited = $arrData["is_submited"] ?? false;

        $proposal = $report->proposal;
        $application = DB::transaction(function () use (
            $arrData,
            $isSubmited,
            $report,
            $proposal,
            $createComment,
            $updateApprovement,
            $updateApprovementStep
        ) {
            $user = User::find(Auth::id());
            $step = $report->approvementStep->step ?? 0;
            $roleId = CommentHelper::determineRoleByStep($step);

            $arrComments = [
                "project_details" => $arrData["project_details"] ?? "",
                "objectives_project" => $arrData["objectives_project"] ?? "",
                "objectives_achievement" => $arrData["objectives_achievement"] ?? "",
                "technology" => $arrData["technology"] ?? "",
                "assessment" => $arrData["assessment"] ?? "",
                "additional_funding" => $arrData["additional_funding"] ?? "",
                "benefits" => $arrData["benefits"] ?? "",
                "report" => $arrData["report"] ?? "",
            ];

            $approvement = $createComment->execute($report, $user, $arrComments, $roleId);

            if ($isSubmited) {
                $approvementStep = $updateApprovementStep->execute($report, $user);
                $updateApprovement->execute($report, $approvement, $approvementStep, $arrData);


                if (in_array($report->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {

                    if ($report->approval_status == Approvement::STATUS_APPROVED) {
                        $proposal->update([
                            "project_status" => Proposal::STATUS_PRJ_COMPLETED
                        ]);
                    }

                    $report->taskable()->delete();
                    (new ClearMyTaskCache)->execute($user->id);
                } else {
                    (new CreateTaskByStatus)->execute(
                        $approvement,
                        $approvementStep,
                        route("panel.end-of-project.comment", ["report" => $report->id]),
                        route("panel.end-of-project.edit", ["report" => $report->id]),
                        $report->proposal->application_id,
                        $report->proposal->project_title,
                        "EOP"
                    );
                }

                $report = $report->load("approvementStep");
                (new CreateEndProjectCommentNotification)->execute($report, $user, $approvement->status);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.end-of-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Comment/Approval End of Project Report '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }
}
