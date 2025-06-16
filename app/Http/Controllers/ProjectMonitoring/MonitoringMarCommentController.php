<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateComment;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\ProjectMonitoring\Mar\CreateMarCommentNotification;
use App\Actions\ProjectMonitoring\UpdateReportQuarterlyStep;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Mar\FormCommentRequest;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MonitoringMarCommentController extends Controller
{
    protected $policy;
    protected $arrCreateLink;

    public function __construct(MonitoringTrfPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->comment($user, $mar)) abort(403);

        $filters = $request->session()->get('filters');

        $report = $mar->load(
            "proposal",
            "reportMilestone",
            "reportMilestone.milestoneCommercialization",
            "reportMilestone.milestoneExpertiseDevelopment",
            "reportMilestone.milestoneIpr",
            "reportMilestone.milestonePrototype",
            "reportMilestone.milestonePublication",
        );

        $approvement = $report->approvement()
            ->with("user")
            ->where("user_id", "!=", Auth::id())
            ->get();

        $myApprovement = $report->approvement()
            ->with("user")
            ->where("user_id", Auth::id())
            ->first();

        $proposalType = $report->proposal->proposal_type;
        $proposalTypeStr = $proposalType == Proposal::TYPE_TRF ? 'TRF' : 'External Fund';

        $baseRoute = $proposalType == Proposal::TYPE_TRF ? 'panel.monitoring-trf' : 'panel.monitoring-ef';

        return Inertia::render('ProjectMonitoring/Global/Mar/Comments', [
            "title" => "{$proposalTypeStr} Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => (new MonitoringMarShowResource($report))->toArray($request),
                "urlIndex" => route($baseRoute . ".index"),
                "urlSubmit" => route($baseRoute . ".mar.comment", ["mar" => $report->id]),
                "approvement" => $approvement,
                "myApprovement" => $myApprovement,
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(
        FormCommentRequest $request,
        CreateComment $createComment,
        UpdateApprovement $updateApprovement,
        UpdateApprovementStep $updateApprovementStep,
        ReportQuarterly $mar
    ) {
        $user = User::find(Auth::id());
        $arrData = $request->validated();
        $report = $mar;

        if (!$this->policy->comment($user, $mar)) abort(403);

        DB::transaction(function () use (
            $user,
            $arrData,
            $report,
            $createComment,
            $updateApprovement,
            $updateApprovementStep
        ) {
            $step = $report->approvementStep->step ?? 0;
            $roleId = CommentHelper::determineRoleByStep($step);
            $approvement = $createComment->execute($report, $user, $arrData, $roleId);

            if ($arrData['last'] ?? false) {
                $approvementStep = $updateApprovementStep->execute($report, $user);
                $updateApprovement->execute($report, $approvement, $approvementStep, $arrData);
                $report = $report->load("approvementStep");

                if (in_array($report->approval_status, [Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED])) {
                    $report->taskable()->delete();
                    (new ClearMyTaskCache)->execute($user->id);
                } else {
                    $isTrf = $report->proposal->proposal_type == Proposal::TYPE_TRF;

                    (new CreateTaskByStatus)->execute(
                        $approvement,
                        $approvementStep,
                        $isTrf
                            ? route("panel.monitoring-trf.mar.comment", ["mar" => $report->id])
                            : route("panel.monitoring-ef.mar.comment", ["mar" => $report->id]),
                        $isTrf
                            ? route("panel.monitoring-trf.mar.edit", ["mar" => $report->id])
                            : route("panel.monitoring-ef.mar.edit", ["mar" => $report->id]),
                        $report->proposal->application_id,
                        $report->proposal->project_title,
                        "MAR"
                    );
                }

                $report = $report->load("approvementStep");
                (new CreateMarCommentNotification)->execute($report, $user, $approvement->status);
            }
        });

        if ($request->last) {
            $routeStr = $report->proposal->proposal_type == Proposal::TYPE_TRF
                ? "panel.monitoring-trf.index"
                : "panel.monitoring-ef.index";

            return redirect()->route($routeStr)
                ->with("message", [
                    "status" => "success",
                    "message" => "Comment Milestone Achievement Report  '{$report->proposal->project_title}: {$report->year} Quarter {$report->quarter}' Success!"
                ]);
        }

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Comment Milestone Achievement Report '{$report->proposal->project_title}: {$report->year} Quarter {$report->quarter}' Success!"
            ]);
    }
}
