<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabBulkData;
use App\Actions\KpiMonitoring\CreateImportedGermplasmBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\AnalyticalServiceLabUploadBulkRequest;
use App\Http\Requests\KpiMonitoring\ImportedGermplasmUploadBulkRequest;
use App\Policies\ImportedGermplasmPolicy;
use App\Policies\PublicationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ImportedGermplasmUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(ImportedGermplasmPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function create(Request $request)
    {
        if (!$this->policy->createBulk($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        $breadcrumbs = [
            [
                "url" => "#",
                "label" => "R&D LKM KPI Monitoring",
            ],
            [
                "url" => route("panel.imported-germplasm.index"),
                "label" => "Imported Germplasm",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Imported Germplasm | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Imported Germplasm",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "project_leader", "year", "quarter", "no_of_germplasm"],
                "urlTemplate" => url("/assets/template/07-imported-germplasm.xlsx"),
                "urlSubmit" => route("panel.imported-germplasm.bulk-store"),
                "urlIndex" => route("panel.imported-germplasm.index")
            ]
        ]);
    }

    public function store(ImportedGermplasmUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            (new CreateImportedGermplasmBulkData)->execute($arrData["file_data"] ?? [], $userAuth);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.imported-germplasm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Imported Germplasm Success!"
            ]);
    }

}
