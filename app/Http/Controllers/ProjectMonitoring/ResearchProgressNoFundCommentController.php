<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateComment;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\ProjectMonitoring\ResearchProgressNoFund\CreateResearchProgressNoFundCommentNotification;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\ResearchProgressCommentRequest;
use App\Http\Resources\FileableResource;
use App\Models\Approvement;
use App\Models\ReportResearchProgress;
use App\Models\User;
use App\Policies\ResearchProgressPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResearchProgressNoFundCommentController extends Controller
{
    protected $policy;

    public function __construct(ResearchProgressPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function edit(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->comment($user, $report)) abort(403);

        $filters = $request->session()->get('filters');

        $approvement = $report->approvement()
            ->with("user")
            ->where("user_id", "!=", Auth::id())
            ->get();

        $myApprovement = $report->approvement()
            ->with("user")
            ->where("user_id", Auth::id())
            ->first();

        $report = $report->load(
            "reportType",
            "pslkm",
            "projectTeam",
            "objective",
            "fileable"
        );

        $report->old_files = FileableResource::collection($report->fileable->sortByDesc("id"));

        return Inertia::render('ProjectMonitoring/ResearchProgressNoFund/Comment', [
            "title" => "View Extension Of Project No Fund | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $report,
                "approvement" => $approvement,
                "myApprovement" => $myApprovement,
                "optionsStatus" => CommentHelper::getOptionsStatus(),
                "urlSubmit" => route("panel.research-progress-no-fund.comment", ["report" => $report->id]),
                "urlIndex" => route("panel.research-progress-no-fund.index")
            ]
        ]);
    }

    public function update(
        ResearchProgressCommentRequest $request,
        CreateComment $createComment,
        UpdateApprovement $updateApprovement,
        UpdateApprovementStep $updateApprovementStep,
        ReportResearchProgress $report
    ) {
        $arrData = $request->validated();
        if (!$this->policy->comment($request->user(), $report)) abort(403);

        $arrData = $request->validated();
        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use (
            $arrData,
            $isSubmited,
            $report,
            $createComment,
            $updateApprovement,
            $updateApprovementStep
        ) {
            $user = User::find(Auth::id());
            $step = $report->approvementStep->step ?? 0;
            $roleId = CommentHelper::determineRoleByStep($step);

            $arrComments = [
                "comment" => $arrData["comment"] ?? '',
            ];

            $approvement = $createComment->execute($report, $user, $arrComments, $roleId);

            if ($isSubmited) {
                $approvementStep = $updateApprovementStep->execute($report, $user);
                $updateApprovement->execute($report, $approvement, $approvementStep, $arrData);

                if (in_array($report->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {
                    $report->taskable()->delete();
                    (new ClearMyTaskCache)->execute($user->id);
                } else {
                    (new CreateTaskByStatus)->execute(
                        $approvement,
                        $approvementStep,
                        route("panel.research-progress.comment", ["report" => $report->id]),
                        route("panel.research-progress.edit", ["report" => $report->id]),
                        ' - ',
                        "{$report->project_title} ({$report->year} - {$report->reportType->description})",
                        "Research Progress",
                    );
                }

                $report = $report->load("approvementStep");
                (new CreateResearchProgressNoFundCommentNotification)->execute($report, $user, $approvement->status);
            }

            return $report;
        });

        if ($isSubmited) {
            return redirect()->route('panel.research-progress-no-fund.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Extension Project Application '{$report->project_title}' Success!"
                ]);
        }

        return redirect()->back();
    }
}
