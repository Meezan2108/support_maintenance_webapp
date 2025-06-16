<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Http\Resources\ProjectMonitoring\EndOfProjectShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\ReportResearchProgress;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use App\Policies\ExtensionProjectPolicy;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use App\Policies\ResearchProgressPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ResearchProgressDownloadController extends Controller
{

    protected $policy;

    public function __construct(ResearchProgressPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function show(Request $request, ReportResearchProgress $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $report)) abort(403);

        $mapTeamType = [
            1 => "Project Leader",
            2 => "Researcher"
        ];

        $report = $report->load(
            "reportType",
            "proposal.researcher",
            "proposal.teams",
            "proposal.objectives",
            "proposal.typeOfFund"
        );

        $pdf1 = Pdf::loadView("download-pdf.project-monitoring.research-progress.monitoring", [
            "report" => $report,
            "mapTeamType" => $mapTeamType
        ])->setOption(['dpi' => 110]);


        $m = new Merger();

        $m->addRaw($pdf1->output());

        $nama_file = Str::slug('researchprogress-' . $report->proposal->project_number, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
