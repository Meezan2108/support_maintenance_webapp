<?php

namespace App\Http\Controllers\Documentation;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Http\Resources\ProjectMonitoring\EndOfProjectShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringMarShowResource;
use App\Http\Resources\ProjectMonitoring\MonitoringQfrFormResource;
use App\Models\Documentation;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\ReportResearchProgress;
use App\Models\User;
use App\Policies\DocumentationPolicy;
use App\Policies\EndOfProjectPolicy;
use App\Policies\ExtensionProjectPolicy;
use App\Policies\MonitoringEfPolicy;
use App\Policies\MonitoringTrfPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use App\Policies\RefTablePolicy;
use App\Policies\ResearchProgressPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DocumentationExportController extends Controller
{

    protected $policy;

    public function __construct(DocumentationPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function show(Request $request, Documentation $documentation)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->view($user, $documentation)) abort(403);
        // return $documentation->load("projectLeader", "fileable", "category");

        $pdf1 = Pdf::loadView("download-pdf.documentation.export", [
            "documentation" => $documentation->load("projectLeader", "fileable", "category"),
        ])->setOption(['dpi' => 110]);

        $m = new Merger();

        $m->addRaw($pdf1->output());

        $nama_file = Str::slug('extensionproject-' . $documentation->description, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
