<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormMar;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\Mar\CreateMarSubmitNotification;
use App\Actions\ProjectMonitoring\Mar\UpdateAttachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form8Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form4AttachmentController extends Controller
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

    public function store(Form8Request $request, CreateTask $createTask)
    {
        $arrData = $request->validated();

        $report = ReportQuarterly::query()
            ->where('user_id', Auth::id())
            ->where('report_type', ReportQuarterly::TYPE_MAR)
            ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
            ->first();

        if (!$report) {
            throw ValidationException::withMessages([
                'report' => "You need to fill the tab 1 (Milestone Achievement) before fill this form!"
            ]);
        }

        $proposal = $report->proposal;

        $policy = $proposal->proposal_type == Proposal::TYPE_TRF
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user(), $proposal)) {
            abort(403);
        }

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($report, $createTask, $proposal, $isSubmited, $request, $arrData) {

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new UpdateAttachment)->execute($report, $requestFiles, $arrData);

            $report->update([
                "approval_status" => $isSubmited
                    ? ReportQuarterly::STATUS_SUBMITED
                    : ReportQuarterly::STATUS_DRAFT
            ]);

            if ($isSubmited) {
                $user = User::find(Auth::id());

                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? route("panel.monitoring-trf.mar.comment", ["mar" => $report->id])
                        : route("panel.monitoring-ef.mar.comment", ["mar" => $report->id]),
                    $proposal->application_id ?? '',
                    "{$proposal->project_title} ({$report->year} Q-{$report->quarter})",
                    "MAR",
                    Approvement::STATUS_SUBMITED,
                    $proposal->researcher->division
                );

                (new CreateMarSubmitNotification)->execute($report, $user);
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
                    "message" => "Update Milestone Achievement Report '$reportName' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function update(Form8Request $request, ReportQuarterly $form4, CreateTask $createTask)
    {
        $arrData = $request->validated();

        $report = $form4;

        $proposal = $report->proposal;

        $policy = $proposal->proposal_type == Proposal::TYPE_TRF
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $report)) {
            abort(403);
        }

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use (
            $report,
            $proposal,
            $isSubmited,
            $request,
            $arrData,
            $createTask
        ) {
            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new UpdateAttachment)->execute($report, $requestFiles, $arrData);

            if ($isSubmited) {
                $report->update([
                    "approval_status" => ReportQuarterly::STATUS_RE_SUBMIT
                ]);

                $user = User::find(Auth::id());

                $createTask->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    $proposal->proposal_type == Proposal::TYPE_TRF
                        ? route("panel.monitoring-trf.mar.comment", ["mar" => $report->id])
                        : route("panel.monitoring-ef.mar.comment", ["mar" => $report->id]),
                    $proposal->application_id ?? '',
                    "{$proposal->project_title} ({$report->year} Q-{$report->quarter})",
                    "MAR",
                    Approvement::STATUS_RE_SUBMIT,
                    $proposal->researcher->division
                );

                (new CreateMarSubmitNotification)->execute($report, $user);
            }

            return $report;
        });


        if ($isSubmited) {
            $reportName = "{$report->proposal->project_title}: {$report->year} - Quarter {$report->quarter}";
            return redirect()->route($proposal->proposal_type == 1
                ? 'panel.monitoring-trf.index'
                : 'panel.monitoring-ef.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Update Milestone Achievement Report '$reportName' Success!"
                ]);
        }

        return redirect()->back();
    }
}
