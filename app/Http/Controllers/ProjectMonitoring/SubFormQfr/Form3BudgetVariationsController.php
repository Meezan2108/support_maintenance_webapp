<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormQfr;

use App\Actions\ProjectMonitoring\Mar\UpdateMilestoneAchievement;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Mar\Form1Request;
use App\Http\Requests\ProjectMonitoring\Qfr\Form3Request;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form3BudgetVariationsController extends Controller
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

    public function store(Form3Request $request)
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

        $report = DB::transaction(function () use ($report, $arrData) {
            $reportQuarterlyFinancial = $report->reportQuarterlyFinancial;
            $reportQuarterlyFinancial->update([
                "reasons" => $arrData["reasons"] ?? ""
            ]);

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

    public function update(Form3Request $request, ReportQuarterly $form3)
    {
        $report = $form3;
        $proposal = $report->proposal;
        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $report)) abort(403);

        $arrData = $request->validated();

        $report = DB::transaction(function () use ($report, $arrData) {
            $reportQuarterlyFinancial = $report->reportQuarterlyFinancial;
            $reportQuarterlyFinancial->update([
                "reasons" => $arrData["reasons"] ?? ""
            ]);

            return $report;
        });

        return redirect()->back();
    }
}
