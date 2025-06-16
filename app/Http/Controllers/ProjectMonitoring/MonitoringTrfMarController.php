<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectMonitoring\MonitoringMarFormResource;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringTrfFormResource;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MonitoringTrfMarController extends Controller
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
            ->where("proposal_type", Proposal::TYPE_TRF)
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
                "reportMilestone",
                "reportMilestone.milestoneCommercialization",
                "reportMilestone.milestoneExpertiseDevelopment",
                "reportMilestone.milestoneIpr",
                "reportMilestone.milestonePrototype",
                "reportMilestone.milestonePublication",
            )
            ->where('user_id', $user->id)
            ->where('report_type', ReportQuarterly::TYPE_MAR)
            ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
            ->first();

        return Inertia::render('ProjectMonitoring/TrfMonitoring/Mar/Create', [
            "title" => "TRF Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringMarFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "urlIndex" => route("panel.monitoring-trf.index")
            ]
        ]);
    }

    public function show(Request $request, ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $mar)) abort(403);

        $filters = $request->session()->get('filters');

        $report = $mar->load(
            "reportMilestone",
            "reportMilestone.milestoneCommercialization",
            "reportMilestone.milestoneExpertiseDevelopment",
            "reportMilestone.milestoneIpr",
            "reportMilestone.milestonePrototype",
            "reportMilestone.milestonePublication",
        );

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        return Inertia::render('ProjectMonitoring/TrfMonitoring/Mar/Show', [
            "title" => "TRF Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringMarShowResource($report))->toArray($request) : null,
                "approvement" => $approvement,
                "urlIndex" => route("panel.monitoring-trf.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $mar)) abort(403);

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("id", $mar->proposal_id)
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        $report = $mar->load(
            "reportMilestone",
            "reportMilestone.milestoneCommercialization",
            "reportMilestone.milestoneExpertiseDevelopment",
            "reportMilestone.milestoneIpr",
            "reportMilestone.milestonePrototype",
            "reportMilestone.milestonePublication",
        );

        return Inertia::render('ProjectMonitoring/TrfMonitoring/Mar/Edit', [
            "title" => "Edit TRF Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => (new MonitoringMarFormResource($report))->toArray($request),
                "listLinkCreate" => $this->arrCreateLink,
                "projectNumbers" => $projectNumbers,
                "urlIndex" => route("panel.monitoring-trf.index")
            ]
        ]);
    }

    public function destroy(ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        $report = $mar;

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
