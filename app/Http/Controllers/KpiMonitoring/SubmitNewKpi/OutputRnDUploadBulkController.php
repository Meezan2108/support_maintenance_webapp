<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateOutputRndBulkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\OutputRnDUploadBulkRequest;
use App\Policies\OutputRnDPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OutputRnDUploadBulkController extends Controller
{

    protected $policy;

    public function __construct(OutputRnDPolicy $policy)
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
                "url" => route("panel.rnd-output.index"),
                "label" => "Ouput R&D",
            ],
            [
                "url" => "#",
                "label" => "Upload Bulk",
            ],
        ];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Global/UploadBulk', [
            "title" => "Upload Bulk Output R&D | R&D LKM KPI Monitoring",
            "additional" => [
                "title" => "Upload Bulk Output R&D",
                "user" => $request->user(),
                "filters" => $filters,
                "breadcrumbs" => $breadcrumbs,
                "header" => ["no", "project_leader", "output", "type", "status", "date", "source", "project_number"],
                "urlTemplate" => url("/assets/template/03-rnd-output.xlsx"),
                "urlSubmit" => route("panel.rnd-output.bulk-store"),
                "urlIndex" => route("panel.rnd-output.index")
            ]
        ]);
    }

    public function store(OutputRnDUploadBulkRequest $request)
    {
        $userAuth = $request->user();
        if (!$this->policy->createBulk($userAuth)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $userAuth) {

            $arrData = $request->validated();

            (new CreateOutputRndBulkData)->execute($arrData["file_data"] ?? [], $userAuth);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.rnd-output.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Upload Bulk Output R&D Success!"
            ]);
    }

}
