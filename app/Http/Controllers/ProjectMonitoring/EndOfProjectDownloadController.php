<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectMonitoring\EndOfProjectShowResource;
use App\Models\RefReportEopBenefitsGroup;
use App\Models\ReportEndProject;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Str;


class EndOfProjectDownloadController extends Controller
{

    protected $policy;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function show(Request $request, ReportEndProject $report)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $report)) abort(403);

        $report = $report->load(
            "proposal.researcher",
            "proposal.typeOfFund",
            "proposal.teams",
            "proposal.objectives",

            "proposal.researchType",
            "proposal.researchCluster",
            "proposal.seoCategory",
            "proposal.seoGroup",
            "proposal.seoArea",
            "proposal.primaryResearchField.category",
            "proposal.primaryResearchField.group",
            "proposal.primaryResearchField.area",
            "proposal.secondaryResearchField.category",
            "proposal.secondaryResearchField.group",
            "proposal.secondaryResearchField.area",
            "objective"
        );

        $reportEop = $report ? (new EndOfProjectShowResource($report))->toArray($request) : null;

        $questionsBenefit = RefReportEopBenefitsGroup::with(
            "section.question"
        )->get();

        $mapTeamType = [
            1 => "Project Leader",
            2 => "Researcher"
        ];

        $pdf1 = Pdf::loadView("download-pdf.project-monitoring.eop.monitoring", [
            "report" => $reportEop,
            "eop" => $report,
            "mapTeamType" => $mapTeamType,
            "questionsBenefit" => $questionsBenefit
        ])->setOption(['dpi' => 110]);


        $m = new Merger();

        $m->addRaw($pdf1->output());

        $nama_file = Str::slug('endofproject-' . $reportEop['project_details']['proposal']['project_number'], '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
