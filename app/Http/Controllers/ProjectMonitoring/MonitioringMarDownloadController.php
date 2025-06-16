<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\ReportQuarterly;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class MonitioringMarDownloadController extends Controller
{

    protected $policy;
    protected $policyTrf;
    protected $policyEf;

    public function __construct(MonitoringTrfPolicy $policyTrf, MonitoringEfPolicy $policyEf)
    {
        $this->policyTrf = $policyTrf;
        $this->policyEf = $policyEf;
    }


    public function show(Request $request, ReportQuarterly $mar)
    {
        $this->policy = $mar->proposal_type == ReportQuarterly::TYPE_TRF
            ? $this->policyTrf : $this->policyEf;

        if (!$this->policy->view(Auth::user(), $mar)) abort(403);

        $filters = $request->session()->get('filters');
        // return $mar;
        $report = $mar->load(
            "proposal",
            "reportMilestone",
            "reportMilestone.milestoneCommercialization",
            "reportMilestone.milestoneExpertiseDevelopment",
            "reportMilestone.milestoneIpr",
            "reportMilestone.milestonePrototype",
            "reportMilestone.milestonePublication",
        );
        // return $report;

        $report2 = $report ? (new MonitoringMarShowResource($report))->toArray($request) : null;

        $milestones = ProposalMilestone::query()
            ->where("proposal_id", $report->proposal_id ?? false)
            ->when($report->year, function ($query, $year) {
                return $query->where(DB::raw("YEAR([from])"), $year);
            })
            ->when($report->year && $report->quarter, function ($query) use ($report) {
                return $query->filterByQuarter($report->year, $report->quarter);
            })
            ->get();
            // return $milestones;
        $pdf1 = Pdf::loadView("download-pdf.project-monitoring.mar.monitoring", [
            "mar" => $report,
            "report" => $report2,
            "milestones" => $milestones
            // "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration),
        ])->setOption(['dpi' => 110]);


        $m = new Merger();

        $m->addRaw($pdf1->output());
        // $m->addRaw($pdf2->output());
        // $m->addRaw($pdf3->output());

        $nama_file = Str::slug('monitioring-'.$mar->report_type_code.'-'. $mar->proposal->project_number, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
