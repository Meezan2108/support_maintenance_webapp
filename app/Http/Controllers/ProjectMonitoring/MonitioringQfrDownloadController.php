<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\Proposal;
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
use Illuminate\Support\Str;


class MonitioringQfrDownloadController extends Controller
{

    protected $policy;
    protected $policyTrf;
    protected $policyEf;

    public function __construct(MonitoringTrfPolicy $policyTrf, MonitoringEfPolicy $policyEf)
    {
        $this->policyTrf = $policyTrf;
        $this->policyEf = $policyEf;
    }


    public function show(Request $request, ReportQuarterly $qfr)
    {
        $this->policy = $qfr->proposal_type == ReportQuarterly::TYPE_TRF
            ? $this->policyTrf : $this->policyEf;

        if (!$this->policy->view(Auth::user(), $qfr)) abort(403);

        $filters = $request->session()->get('filters');
        
        $report = $qfr->load(
            "proposal",
            "proposal.projectCost.detail",
            "reportQuarterlyFinancial.detail",
        );

        $projectCostYear = [];
        foreach ($report->proposal->projectCost as $k => $cost) {
            foreach ($cost->detail as $kk => $costDetail) {
                if (!isset($projectCostYear[$costDetail->year])) {
                    $projectCostYear[$costDetail->year]['year'] = $costDetail->year;
                    $projectCostYear[$costDetail->year]['total'] = $costDetail->cost;
                }else {
                    $projectCostYear[$costDetail->year]['total'] += $costDetail->cost;
                }
            }
        }

        $report2 = $report ? (new MonitoringQfrFormResource($report))->toArray($request) : null;

        $actual_project_expenditure = [];
        foreach ($report2['financial_progress']['actual_project_expenditure'] as $key => $value) {
            $actual_project_expenditure[$value['ref_project_cost_series_id']] = $value;
        }
        $projectCostSeries = RefProjectCostSeries::query()
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $approvement = $report->approvement()
            ->with("user")
            ->get();

        $totalProjectCostSeries = [
            'approved' => 0,
            'received' => 0,
            'expenditure' => 0,
        ];

        foreach ($projectCostSeries as $k => $v) {
            $v->detail = $actual_project_expenditure[$v->id] ?? [];
            $totalProjectCostSeries['approved'] += $actual_project_expenditure[$v->id]['total_approved'];
            $totalProjectCostSeries['received'] += $actual_project_expenditure[$v->id]['total_recieved'];
            $totalProjectCostSeries['expenditure'] += $actual_project_expenditure[$v->id]['total_expenditure'];
        }

        $pdf1 = Pdf::loadView("download-pdf.project-monitoring.qfr.monitoring", [
            "qfr" => $report,
            "report" => $report2,
            "projectCostYear" => $projectCostYear,
            "projectCostSeries" => $projectCostSeries,
            "approvement" => $approvement,
            "totalProjectCostSeries" => $totalProjectCostSeries,
            // "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration),
        ])->setOption(['dpi' => 110]);


        $m = new Merger();

        $m->addRaw($pdf1->output());
        // $m->addRaw($pdf2->output());
        // $m->addRaw($pdf3->output());

        $nama_file = Str::slug('monitioring-'.$qfr->report_type_code.'-'. $qfr->proposal->project_number, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
