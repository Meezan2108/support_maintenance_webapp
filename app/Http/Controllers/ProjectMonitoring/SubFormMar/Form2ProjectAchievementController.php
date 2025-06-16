<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormMar;

use App\Actions\ProjectMonitoring\Mar\UpdateProjectAchievement;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\Mar\Form2Request;

use App\Models\ReportQuarterly;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form2ProjectAchievementController extends Controller
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

    public function store(Form2Request $request, UpdateProjectAchievement $updateProjectAchievement)
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
        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user())) abort(403);

        $report = DB::transaction(function () use ($report, $arrData, $updateProjectAchievement) {

            $updateProjectAchievement->execute($report, $arrData);

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

    public function update(Form2Request $request, ReportQuarterly $form2, UpdateProjectAchievement $updateProjectAchievement)
    {
        $report = $form2;

        $proposal = $report->proposal;
        $policy = $proposal->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $report)) abort(403);

        $arrData = $request->validated();

        $report = DB::transaction(function () use ($report, $arrData, $updateProjectAchievement) {

            $updateProjectAchievement->execute($report, $arrData);

            return $report;
        });

        return redirect()->back();
    }
}
