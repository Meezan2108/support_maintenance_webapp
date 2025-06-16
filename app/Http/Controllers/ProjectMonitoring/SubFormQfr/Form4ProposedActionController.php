<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormQfr;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\Qfr\CreateQfrSubmitNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Qfr\Form4Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form4ProposedActionController extends Controller
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

    public function store(Form4Request $request, CreateTask $createTask)
    {
        $report = ReportQuarterly::query()
            ->where('user_id', Auth::id())
            ->where('report_type', ReportQuarterly::TYPE_QFR)
            ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
            ->first();

        if (!$report) {
            throw ValidationException::withMessages([
                'report' => "You need to fill the tab 1 (Project Details) & tab 2 (Financial Progress)  before fill this form!"
            ]);
        }

        $proposal = $report->proposal;

        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user(), $proposal)) abort(403);

        $arrData = $request->validated();

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($report, $arrData, $isSubmited, $createTask) {
            $reportQuarterlyFinancial = $report->reportQuarterlyFinancial;
            $reportQuarterlyFinancial->update([
                "proposed_action" => $arrData["proposed_action"] ?? ""
            ]);

            $report->update([
                "approval_status" => $isSubmited
                    ? ReportQuarterly::STATUS_SUBMITED
                    : ReportQuarterly::STATUS_DRAFT
            ]);


            if ($isSubmited) {
                $user = User::find(Auth::id());

                $proposal = $report->proposal;
                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? route("panel.monitoring-trf.qfr.comment", ["qfr" => $report->id])
                        : route("panel.monitoring-ef.qfr.comment", ["qfr" => $report->id]),
                    $proposal->application_id,
                    "{$proposal->project_title} ({$report->year} Q-{$report->quarter})",
                    "QFR",
                    Approvement::STATUS_SUBMITED
                );

                (new CreateQfrSubmitNotification)->execute($report, $user);
            }

            return $report;
        });

        if ($isSubmited) {
            $reportName = "{$report->proposal->project_title}: {$report->year} - Quarter {$report->quarter}";
            return redirect()->route($proposal->proposal_type == Proposal::TYPE_TRF
                ? 'panel.monitoring-trf.index'
                : 'panel.monitoring-ef.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create Quarterly Financial Report '$reportName' Success!"
                ]);;
        }

        return redirect()->back();
    }

    public function update(Form4Request $request, ReportQuarterly $form4, CreateTask $createTask)
    {
        $report = $form4;
        $proposal = $report->proposal;
        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $report)) abort(403);

        $arrData = $request->validated();

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($report, $arrData, $isSubmited, $createTask) {
            $reportQuarterlyFinancial = $report->reportQuarterlyFinancial;
            $reportQuarterlyFinancial->update([
                "proposed_action" => $arrData["proposed_action"] ?? ""
            ]);

            if ($isSubmited) {
                $report->update([
                    "approval_status" => ReportQuarterly::STATUS_RE_SUBMIT
                ]);

                $user = User::find(Auth::id());

                $proposal = $report->proposal;
                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? route("panel.monitoring-trf.qfr.comment", ["qfr" => $report->id])
                        : route("panel.monitoring-ef.qfr.comment", ["qfr" => $report->id]),
                    $proposal->application_id,
                    "{$proposal->project_title} ({$report->year} Q-{$report->quarter})",
                    "QFR",
                    Approvement::STATUS_RE_SUBMIT,
                    $proposal->researcher->division
                );

                (new CreateQfrSubmitNotification)->execute($report, $user);
            }
            return $report;
        });

        if ($isSubmited) {
            $reportName = "{$report->proposal->project_title}: {$report->year} - Quarter {$report->quarter}";
            return redirect()->route($proposal->proposal_type == Proposal::TYPE_TRF
                ? 'panel.monitoring-trf.index'
                : 'panel.monitoring-ef.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Update Quarterly Financial Report '$reportName' Success!"
                ]);
        }

        return redirect()->back();
    }
}
