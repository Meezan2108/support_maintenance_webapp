<?php

namespace App\Http\Controllers\KpiMonitoring\MyKpi;

use App\Actions\KpiMonitoring\GetMyKpiDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\MyKpiSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\KpiMonitoring\MyKpiResource;
use App\Models\AnalyticalServiceLab;
use App\Models\Commercialization;
use App\Models\ImportedGermplasm;
use App\Models\IPR;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Publication;
use App\Models\Recognition;
use App\Policies\MyKpiPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyKpiController extends Controller
{

    protected $policy;

    public function __construct(MyKpiPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetMyKpiDatatables $get_mykpi, MyKpiSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $mykpis = $get_mykpi->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/MyKpi/Index', [
            "title" => "List My KPI Achievement | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => MyKpiResource::collection($mykpis),
                "filters" => $filters,
                "columns" => $get_mykpi->getColumns(),
                "urlIndex" => route("panel.my-kpi.index")
            ]
        ]);
    }

    public function show(Request $request, KpiAchievement $mykpi)
    {
        if (!$this->policy->view($request->user(), $mykpi)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $kpiModel = $mykpi->reff;
        $detail = $this->getDetailData($kpiModel);

        return Inertia::render('KpiMonitoring/MyKpi/Show', [
            "title" => "View My KPI Achievement | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $detail["initValue"] ?? null,
                "title" => $detail["title"] ?? '',
                "type" => $detail['type'] ?? false,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $mykpi),
                "urlIndex" => route("panel.my-kpi.index")
            ]
        ]);
    }

    protected function getDetailData(Model $reffObject)
    {
        $arrData = null;

        switch (get_class($reffObject)) {
            case Publication::class:
                $arrData = [
                    "title" => "View Publication",
                    "initValue" => $reffObject->load(
                        "kpiAchievement.user",
                        "kpiAchievement.researcher",
                        "type",
                        "proposal",
                        "researcherInvolved"
                    ),
                    "type" => "publication"
                ];
                break;
            case Recognition::class:
                $reffObject = $reffObject->load("kpiAchievement.user", "proposal", "fileable");
                $reffObject->old_file = FileableResource::collection($reffObject->fileable->sortByDesc("id"));
                $reffObject->recognition_type = $reffObject->recognitionType();
                $arrData = [
                    "title" => "View Recognition",
                    "initValue" => $reffObject->load("researcherInvolved"),
                    "type" => "recognition"
                ];
                break;
            case IPR::class:
                $arrData = [
                    "title" => "View IPR",
                    "initValue" => $reffObject->load("kpiAchievement.user", "patent", "proposal"),
                    "type" => "ipr"
                ];
                break;
            case OutputRnd::class:
                $arrData = [
                    "title" => "View R&D Output",
                    "initValue" => $reffObject->load("kpiAchievement.user", "output_type", "output_status", "proposal"),
                    "type" => "output_rnd"
                ];
                break;
            case Commercialization::class:
                $arrData = [
                    "title" => "View Commercialization",
                    "initValue" => $reffObject->load("kpiAchievement.user", "output_type", "proposal"),
                    "type" => "commercialization"
                ];
                break;
            case AnalyticalServiceLab::class;
                $arrData = [
                    "title" => "View Analytical Service Lab",
                    "initValue" => $reffObject->load("kpiAchievement.user", "proposal"),
                    "type" => "asl"
                ];
                break;
            case ImportedGermplasm::class;
                $arrData = [
                    "title" => "View Imported Germplasm",
                    "initValue" => $reffObject->load("kpiAchievement.user", "proposal"),
                    "type" => "imported_germplasm"
                ];
                break;
            default:
                $arrData = null;
        }

        return $arrData;
    }
}
