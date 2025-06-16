<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateComment;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\ProjectMonitoring\ExtensionProject\CreateExtensionProjectCommentNotification;
use App\Actions\ProjectMonitoring\ExtensionProject\SyncMilestone;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\ExtensionProjectCommentRequest;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\User;
use App\Policies\ExtensionProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExtensionOfProjectCommentController extends Controller
{
    protected $policy;

    public function __construct(ExtensionProjectPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function edit(Request $request, ExtensionProject $application)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->comment($user, $application)) abort(403);

        $filters = $request->session()->get('filters');

        $approvement = $application->approvement()
            ->with("user")
            ->where("user_id", "!=", Auth::id())
            ->get();

        $myApprovement = $application->approvement()
            ->with("user")
            ->where("user_id", Auth::id())
            ->first();

        $proposal = $application->proposal;

        $extensionProjects = $proposal->extensionProject()
            ->with("granttchart")
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->where("id", "<", $application->id)
            ->get();

        return Inertia::render('ProjectMonitoring/ExtensionOfProject/Comment', [
            "title" => "Comment/Approval Extension Of Project | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $application->load(["proposal.milestones" => function ($query) {
                    return $query->where("type", ProposalMilestone::TYPE_PROPOSAL);
                }, "proposal.researcher", "granttchart"]),
                "otherMilestones" => $extensionProjects,
                "approvement" => $approvement,
                "myApprovement" => $myApprovement,
                "optionsStatus" => CommentHelper::getOptionsStatus(),
                "urlIndex" => route("panel.extension-project.index"),
                "urlSubmit" => route("panel.extension-project.comment", ["application" => $application->id])
            ]
        ]);
    }

    public function update(
        ExtensionProjectCommentRequest $request,
        ExtensionProject $application,
        CreateComment $createComment,
        UpdateApprovement $updateApprovement,
        UpdateApprovementStep $updateApprovementStep
    ) {
        if (!$this->policy->comment($request->user(), $application)) abort(403);

        $arrData = $request->validated();
        $isSubmited = $arrData["is_submited"] ?? false;

        $proposal = $application->proposal;
        $application = DB::transaction(function () use (
            $arrData,
            $isSubmited,
            $application,
            $createComment,
            $updateApprovement,
            $updateApprovementStep
        ) {
            $user = User::find(Auth::id());
            $step = $application->approvementStep->step ?? 0;
            $roleId = CommentHelper::determineRoleByStep($step);

            $arrComments = [
                "comment" => $arrData["comment"] ?? "-"
            ];

            $approvement = $createComment->execute($application, $user, $arrComments, $roleId);

            if ($isSubmited) {
                $approvementStep = $updateApprovementStep->execute($application, $user);

                /**
                 * @var ExtensionProject
                 */
                $application = $updateApprovement->execute($application, $approvement, $approvementStep, $arrData);

                if ($application->approval_status == Approvement::STATUS_APPROVED) {
                    (new SyncMilestone)->execute($application);
                }

                if (in_array($application->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {
                    $application->taskable()->delete();
                    (new ClearMyTaskCache)->execute($user->id);
                } else {
                    (new CreateTaskByStatus)->execute(
                        $approvement,
                        $approvementStep,
                        route("panel.extension-project.comment", ["application" => $application->id]),
                        route("panel.extension-project.edit", ["application" => $application->id]),
                        $application->proposal->application_id,
                        $application->proposal->project_title,
                        "Extension"
                    );
                }

                $application = $application->load("approvementStep");
                (new CreateExtensionProjectCommentNotification)->execute($application, $user, $approvement->status);
            }
        });

        if ($isSubmited) {
            return redirect()->route('panel.extension-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Comment Extension Project Application '{$proposal->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }
}
