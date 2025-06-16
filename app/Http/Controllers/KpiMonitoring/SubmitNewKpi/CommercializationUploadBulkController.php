<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateCommercializationBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\CommercializationUploadBulkRequest;
use App\Policies\CommercializationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommercializationUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(CommercializationPolicy $policy)
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
                "url" => route("panel.commercialization.index"),
                "label" => "Commercialization",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Commercialization | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Commercialization",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "date", "project_leader", "name", "taker", "category", "project_number"],
                "urlTemplate" => url("/assets/template/05-commercialization.xlsx"),
                "urlSubmit" => route("panel.commercialization.bulk-store"),
                "urlIndex" => route("panel.commercialization.index")
            ]
        ]);
    }

    public function store(CommercializationUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            (new CreateCommercializationBulkData)->execute($arrData["file_data"] ?? [], $userAuth);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.commercialization.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Commercialization Success!"
            ]);
    }

}
