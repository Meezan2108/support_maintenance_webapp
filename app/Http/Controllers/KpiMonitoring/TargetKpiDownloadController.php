<?php

namespace App\Http\Controllers\KpiMonitoring;

use App\Actions\KpiMonitoring\GetTargetKpiByResearcherDatatables;
use App\Actions\KpiMonitoring\PreparePrintAnalyticalServiceLab;
use App\Actions\KpiMonitoring\PreparePrintCommercialization;
use App\Actions\KpiMonitoring\PreparePrintImportedGermplasm;
use App\Actions\KpiMonitoring\PreparePrintIpr;
use App\Actions\KpiMonitoring\PreparePrintOutputRnd;
use App\Actions\KpiMonitoring\PreparePrintProposal;
use App\Actions\KpiMonitoring\PreparePrintPublications;
use App\Actions\KpiMonitoring\PreparePrintRecognitions;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\TargetKpiByResearcherSearchRequest;
use App\Http\Resources\KpiMonitoring\TargetKpiByResearcherResource;
use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Publication;
use App\Models\Recognition;
use App\Models\RefPubType;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use App\Models\User;
use App\Policies\TargetKpiPolicy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TargetKpiDownloadController extends Controller
{

    protected $policy;
    protected $policyTrf;
    protected $policyEf;

    public function __construct(TargetKpiPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function index(
        GetTargetKpiByResearcherDatatables $datatables,
        TargetKpiByResearcherSearchRequest $request
    ) {
        $user = $request->user();
        if (!$this->policy->viewAny($user)) {
            abort(403);
        }

        $filters = $request->validated();
        $list = $datatables->execute($filters);

        $years = TargetKpi::query()
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($user) {
                return $query->where("user_id", $user->id);
            })
            ->select("year")
            ->groupBy("year")
            ->get()
            ->map(function ($item) {
                return [
                    "id" => $item->year,
                    "description" => $item->year
                ];
            });

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/TargetKpi/IndexDownload', [
            "title" => "List Download KPI Achievement | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => TargetKpiByResearcherResource::collection($list),
                "filters" => $filters,
                "years" => $years,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "isResearcher" => $user->hasExactRoles(["Researcher"]),
                "urlDownload" => route("panel.target-kpi.download.index"),
                "urlIndex" => route("panel.target-kpi.download.index")
            ]
        ]);
    }

    public function show(Request $request, int $year)
    {
        $user = User::find(Auth::id());
        $researcher = User::find($request->researcher_id);

        if ($user->hasExactRoles(["Researcher"]) && optional($researcher)->id != $user->id) {
            abort(403);
        }

        $targetCategories = RefTargetKpiCategory::with("subCategory")
            ->whereNull("parent_id")
            ->get();

        $targetPeriod = RefTargetKpiPeriod::all();

        $targets = TargetKpi::query()
            ->where("approval_status", Approvement::STATUS_APPROVED)
            ->when($researcher, function ($query) use ($researcher) {
                return $query->where("user_id", $researcher->id);
            })
            ->where("year", $year)
            ->groupBy("year", "period_id", "category_id", "sub_category_id")
            ->select("year",  "period_id", "category_id", "sub_category_id", DB::raw("SUM([target]) as target"))
            ->get();

        if ($targets->count() == 0) abort(404);

        // PROPOSAL
        $proposalKpi = (new PreparePrintProposal)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // PUBLICATIONS
        $publicationsKpi = (new PreparePrintPublications)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // RECOGNITIONS
        $recogintionKpi = (new PreparePrintRecognitions)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // OUTPUT RND
        $outputKpi = (new PreparePrintOutputRnd)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );


        // IPR
        $iprKpi = (new PreparePrintIpr)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // Commercialization
        $commercializationKpi = (new PreparePrintCommercialization)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // Analytical Service Lab
        $aslKpi = (new PreparePrintAnalyticalServiceLab)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        // Germplasm
        $importedGermplasmKpi = (new PreparePrintImportedGermplasm)->execute(
            $year,
            $researcher,
            $targetCategories,
            $targetPeriod,
            $targets
        );

        $pdf = Pdf::loadView("download-pdf.kpi-monitoring.target-kpi.target-kpi", [
            "year" => $year,
            "proposal" => $proposalKpi,
            "publications" => $publicationsKpi,
            "recognitions" => $recogintionKpi,
            "outputRnd" => $outputKpi,
            "ipr" => $iprKpi,
            "commercialization" => $commercializationKpi,
            "asl" => $aslKpi,
            "importedGermplasm" => $importedGermplasmKpi
        ])->setOption(['dpi' => 110]);

        return $pdf->stream("target-kpi.pdf");
    }
}
