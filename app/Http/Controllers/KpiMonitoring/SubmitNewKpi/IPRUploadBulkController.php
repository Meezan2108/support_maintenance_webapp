<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateIPRBulkData;
use App\Actions\KpiMonitoring\CreateOutputRndBulkData;
use App\Actions\KpiMonitoring\CreatePublicationSubmitNotification;
use App\Actions\KpiMonitoring\CreatePublicationSubmitTask;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\IPRUploadBulkRequest;
use App\Http\Requests\KpiMonitoring\OutputRnDUploadBulkRequest;
use App\Policies\IPRPolicy;
use App\Policies\PublicationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IPRUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(IPRPolicy $policy)
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
                "url" => route("panel.ipr.index"),
                "label" => "Intellectual Property Right",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk IPR | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Intellectual Property Right",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "date", "output", "project_leader", "type", "project_number"],
                "urlTemplate" => url("/assets/template/04-ipr.xlsx"),
                "urlSubmit" => route("panel.ipr.bulk-store"),
                "urlIndex" => route("panel.ipr.index")
            ]
        ]);
    }

    public function store(IPRUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            (new CreateIPRBulkData)->execute($arrData["file_data"] ?? [], $userAuth);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ipr.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk IPR Success!"
            ]);
    }

}
