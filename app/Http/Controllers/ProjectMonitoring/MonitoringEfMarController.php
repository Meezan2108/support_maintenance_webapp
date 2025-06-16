<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectMonitoring\MonitoringMarFormResource;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Models\Proposal;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\MonitoringEfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MonitoringEfMarController extends Controller
{
    protected $policy;
    protected $arrCreateLink;

    public function __construct(MonitoringEfPolicy $policy)
    {
        $this->policy = $policy;

        $this->arrCreateLink = [
            [
                "href" => route("panel.monitoring-ef.mar.create"),
                "description" => "Milestone Achievement Report (MAR)"
            ],
            [
                "href" => route("panel.monitoring-ef.qfr.create"),
                "description" => "Quarterly Financial Report (QFR)"
            ]
        ];
    }

    public function create(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->create($user)) {
            abort(403);
        }

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
                "reportMilestone",
                "reportMilestone.milestoneCommercialization",
                "reportMilestone.milestoneExpertiseDevelopment",
                "reportMilestone.milestoneIpr",
                "reportMilestone.milestonePrototype",
                "reportMilestone.milestonePublication",
                "reportMilestone.fileable"
            )
            ->where('user_id', $user->id)
            ->where('report_type', ReportQuarterly::TYPE_MAR)
            ->where('approval_status', ReportQuarterly::STATUS_DRAFT)
            ->first();

        return Inertia::render('ProjectMonitoring/EfMonitoring/Mar/Create', [
            "title" => "External Fund Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringMarFormResource($report))->toArray($request) : null,
                "projectNumbers" => $projectNumbers,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function show(Request $request, ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $mar)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $report = $mar->load(
            "reportMilestone",
            "reportMilestone.milestoneCommercialization",
            "reportMilestone.milestoneExpertiseDevelopment",
            "reportMilestone.milestoneIpr",
            "reportMilestone.milestonePrototype",
            "reportMilestone.milestonePublication",
            "reportMilestone.fileable"
        );

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        return Inertia::render('ProjectMonitoring/EfMonitoring/Mar/Show', [
            "title" => "External Fund Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => $report ? (new MonitoringMarShowResource($report))->toArray($request) : null,
                "approvement" => $approvement,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function edit(Request $request, ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->update($user, $mar)) {
            abort(403);
        }

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
            "reportMilestone.fileable"
        );

        return Inertia::render('ProjectMonitoring/EfMonitoring/Mar/Edit', [
            "title" => "Edit External Fund Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "filters" => $filters,
                "initActiveTab" => $request->active_tab,
                "initValue" => (new MonitoringMarFormResource($report))->toArray($request),
                "listLinkCreate" => $this->arrCreateLink,
                "projectNumbers" => $projectNumbers,
                "urlIndex" => route("panel.monitoring-ef.index")
            ]
        ]);
    }

    public function destroy(ReportQuarterly $mar)
    {
        $user = User::find(Auth::id());
        $report = $mar;

        if (!$this->policy->delete($user, $report)) {
            abort(403);
        }

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
