<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormMar;

use App\Actions\ProjectMonitoring\Mar\CheckExistingReport;
use App\Actions\ProjectMonitoring\Mar\ResetMilestoneAchievement;
use App\Actions\ProjectMonitoring\Mar\UpdateMilestoneAchievement;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Mar\Form1Request;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form1MilestoneAchievementController extends Controller
{

    protected $trfPolicy;
    protected $externalPolicy;

    protected $user;

    public function __construct(MonitoringTrfPolicy $trfPolicy, MonitoringEfPolicy $externalFundPolicy)
    {
        $this->trfPolicy = $trfPolicy;
        $this->externalPolicy = $externalFundPolicy;
        $this->user = Auth::user();
    }

    public function store(
        Form1Request $request,
        UpdateMilestoneAchievement $updateMilestoneAchievement,
        CheckExistingReport $checkExistingReport,
        ResetMilestoneAchievement $resetMilestoneAchievement
    ) {
        $arrData = $request->validated();
        $proposal = Proposal::find($arrData['proposal_id']);

        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user(), $proposal)) abort(403);


        if ($reportExist = $checkExistingReport->execute($proposal, $arrData["year"], $arrData["quarter"])) {
            $url = $proposal->proposal_type == Proposal::TYPE_TRF
                ? route("panel.monitoring-trf.mar.show", ["mar" => $reportExist->id])
                : "";

            throw ValidationException::withMessages([
                'report' => 'This report already created! Report status is '
                    . $reportExist->formatStatus($reportExist->approval_status)
                    . ', <a href="' . $url . '" class="fw-bold text-secondary" target="_blank">View Report</a>.'
            ]);
        }

        $report = DB::transaction(function () use (
            $proposal,
            $arrData,
            $updateMilestoneAchievement,
            $resetMilestoneAchievement
        ) {
            $user = User::find(Auth::id());

            $report = ReportQuarterly::query()
                ->where('user_id', $user->id)
                ->where('report_type', ReportQuarterly::TYPE_MAR)
                ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
                ->first();

            if (!$report) {
                $report = ReportQuarterly::create([
                    'user_id' => Auth::id(),
                    'proposal_id' => $arrData['proposal_id'],
                    'report_type' => ReportQuarterly::TYPE_MAR,
                    'report_type_code' => ReportQuarterly::ARR_TYPE[ReportQuarterly::TYPE_MAR],
                    'year' => $arrData['year'],
                    'quarter' => $arrData['quarter'],
                    'approval_status' => ReportQuarterly::STATUS_DRAFT,
                    'created_by' => Auth::id()
                ]);
            } else {
                if (
                    $report->proposal_id != $arrData["proposal_id"]
                    || $report->year != $arrData["year"]
                    || $report->quarter != $arrData["quarter"]
                ) {
                    // clear milestone action for draft
                    $resetMilestoneAchievement->execute($report);
                }

                $report->update([
                    'proposal_id' => $proposal->id,
                    'year' => $arrData["year"],
                    'quarter' => $arrData["quarter"],
                    'updated_by' => Auth::id()
                ]);
            }

            $updateMilestoneAchievement->execute($report, $arrData);

            return $report;
        });

        return redirect()->back();
    }

    public function show(Request $request, ReportQuarterly $form1)
    {
        $policy = $form1->proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $form1)) abort(403);

        return response([
            'report' => $form1
        ], 200);
    }

    public function update(Form1Request $request, ReportQuarterly $form1, UpdateMilestoneAchievement $updateMilestoneAchievement)
    {
        $report = $form1;
        $proposal = $report->proposal;
        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $report)) abort(403);

        $arrData = $request->validated();

        $report = DB::transaction(function () use ($report, $arrData, $updateMilestoneAchievement) {

            $updateMilestoneAchievement->execute($report, $arrData);

            return $report;
        });

        return redirect()->back();
    }
}
