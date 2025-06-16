<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\AnalyticalServiceLabUploadBulkRequest;
use App\Policies\AnalyticalServiceLabPolicy;
use App\Policies\PublicationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticalServiceLabUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(AnalyticalServiceLabPolicy $policy)
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
                "url" => route("panel.analytical-service-lab.index"),
                "label" => "Analytical Service Lab",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Analytical Service Lab | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Analytical Service Lab",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "project_leader", "year", "quarter", "no_of_analysis", "no_of_sample", "no_of_analysis_protocol"],
                "urlTemplate" => url("/assets/template/06-asl.xlsx"),
                "urlSubmit" => route("panel.analytical-service-lab.bulk-store"),
                "urlIndex" => route("panel.analytical-service-lab.index")
            ]
        ]);
    }

    public function store(AnalyticalServiceLabUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            (new CreateAnalyticalServiceLabBulkData)->execute($arrData["file_data"] ?? [], $userAuth);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.analytical-service-lab.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Analytical Service Lab Success!"
            ]);
    }

}
