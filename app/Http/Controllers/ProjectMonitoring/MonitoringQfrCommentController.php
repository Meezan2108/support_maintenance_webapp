<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\CreateComment;
use App\Actions\CreateTaskByStatus;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\ProjectMonitoring\Qfr\CreateQfrCommentNotification;
use App\Actions\UpdateApprovement;
use App\Actions\UpdateApprovementStep;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Qfr\FormCommentRequest;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MonitoringQfrCommentController extends Controller
{
    protected $policy;
    protected $arrCreateLink;

    public function __construct(MonitoringTrfPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, ReportQuarterly $qfr)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->comment($user, $qfr)) abort(403);

        $filters = $request->session()->get('filters');

        $report = $qfr->load(
            "proposal",
            "reportQuarterlyFinancial.detail"
        );

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

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

        return Inertia::render('ProjectMonitoring/Global/Qfr/Comments', [
            "title" => "{$proposalTypeStr} Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringQfrFormResource($report))->toArray($request) : null,
                "projectCostSeries" => $projectCostSeries,
                "urlIndex" => route($baseRoute . ".index"),
                "urlSubmit" => route($baseRoute . ".qfr.comment", ["qfr" => $report->id]),
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
        ReportQuarterly $qfr
    ) {
        $user = User::find(Auth::id());
        $arrData = $request->validated();
        $report = $qfr;

        if (!$this->policy->comment($user, $qfr)) abort(403);

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
                            ? route("panel.monitoring-trf.qfr.comment", ["qfr" => $report->id])
                            : route("panel.monitoring-ef.qfr.comment", ["qfr" => $report->id]),
                        $isTrf
                            ? route("panel.monitoring-trf.qfr.edit", ["qfr" => $report->id])
                            : route("panel.monitoring-ef.qfr.edit", ["qfr" => $report->id]),
                        $report->proposal->application_id,
                        $report->proposal->project_title,
                        "QFR"
                    );
                }

                $report = $report->load("approvementStep");
                (new CreateQfrCommentNotification)->execute($report, $user, $approvement->status);
            }
        });

        if ($request->last) {
            $routeStr = $report->proposal->proposal_type == Proposal::TYPE_TRF
                ? "panel.monitoring-trf.index"
                : "panel.monitoring-ef.index";

            return redirect()->route($routeStr)
                ->with("message", [
                    "status" => "success",
                    "message" => "Comment Quarterly Report  '{$report->proposal->project_title}: {$report->year} Quarter {$report->quarter}' Success!"
                ]);
        }

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Comment Quarterly Report '{$report->proposal->project_title}: {$report->year} Quarter {$report->quarter}' Success!"
            ]);
    }
}
