<?php

namespace App\Http\Controllers\ProjectMonitoring;

use App\Actions\ProjectMonitoring\GetReportQuarterlyDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\MonitoringSearchRequest;
use App\Http\Resources\ProjectMonitoring\MonitoringTableResource;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\MonitoringTrfPolicy;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MonitoringTrfController extends Controller
{
    protected $policy;

    public function __construct(MonitoringTrfPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetReportQuarterlyDatatables $datatables, MonitoringSearchRequest $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute(Proposal::TYPE_TRF, $filters);

        $request->session()->put('filters', $filters);

        $listLink = [
            [
                "href" => route("panel.monitoring-trf.mar.create"),
                "description" => "Milestone Achievement Report (MAR)"
            ],
            [
                "href" => route("panel.monitoring-trf.qfr.create"),
                "description" => "Quarterly Financial Report (QFR)"
            ]
        ];

        return Inertia::render('ProjectMonitoring/TrfMonitoring/Index', [
            "title" => "TRF Quarterly Monitoring | Research Project Monitoring",
            "additional" => [
                "data" => MonitoringTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "listLinkCreate" => $listLink,
                "urlIndex" => route("panel.monitoring-trf.index")
            ]
        ]);
    }
}
