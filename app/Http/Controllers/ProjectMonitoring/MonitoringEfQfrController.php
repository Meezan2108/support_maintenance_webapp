<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MonitoringEfQfrController extends Controller
{
    protected $policy;
    protected $arrCreateLink;

    public function __construct(MonitoringTrfPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("user_id", $user->id)
            ->where("proposal_type", Proposal::TYPE_EXTERNAL_FUND)
            ->whereIn("project_status", [Proposal::STATUS_PRJ_ON_GOING, Proposal::STATUS_PRJ_EXTENDED])
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $report = ReportQuarterly::query()
            ->with(
                "proposal",
                "reportQuarterlyFinancial.detail"
            )
            ->where('user_id', $user->id)
            ->where('report_type', ReportQuarterly::TYPE_QFR)
            ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
            ->first();

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        return Inertia::render('ProjectMonitoring/EfMonitoring/Qfr/Create', [
            "title" => "TRF Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringQfrFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "projectCostSeries" => $projectCostSeries,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function show(Request $request, ReportQuarterly $qfr)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $qfr)) abort(403);

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
            ->get();

        return Inertia::render('ProjectMonitoring/EfMonitoring/Qfr/Show', [
            "title" => "External Fund Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringQfrFormResource($report))->toArray($request) : null,
                "projectCostSeries" => $projectCostSeries,
                "approvement" => $approvement,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportQuarterly $qfr)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) abort(403);

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("id", $qfr->proposal_id)
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $report = $qfr->load(
            "proposal",
            "reportQuarterlyFinancial.detail"
        );

        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        return Inertia::render('ProjectMonitoring/EfMonitoring/Qfr/Edit', [
            "title" => "Edit External Fund Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringQfrFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "projectCostSeries" => $projectCostSeries,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function destroy(ReportQuarterly $qfr)
    {
        $user = User::find(Auth::id());
        $report = $qfr;

        if (!$this->policy->delete($user, $report)) abort(403);

        $reportName = "{$report->proposal->project_title}: {$report->year} - Quarter {$report->quarter}";
        DB::transaction(function () use ($report, $user) {
            $report->deleted_by = $user->id;
            $report->save();
            $report->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Report '$reportName' Success!"
            ]);
    }
}
