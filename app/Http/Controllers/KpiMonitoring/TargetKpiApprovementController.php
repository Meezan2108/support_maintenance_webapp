<?php

namespace App\Http\Controllers\KpiMonitoring;

use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\TargetKpiApprovementRequest;
use App\Models\TargetKpi;
use App\Policies\TargetKpiPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TargetKpiApprovementController extends Controller
{

    protected $policy;

    public function __construct(TargetKpiPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function edit(Request $request, TargetKpi $target)
    {
        if (!$this->policy->approval($request->user(), $target)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/TargetKpi/Approvement', [
            "title" => "View Agency KPI Target | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $target->load("user", "category", "subCategory", "period"),
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $target),
                "urlApprovement" => route("panel.target-kpi.approvement", ["target" => $target->id]),
                "urlIndex" => route("panel.target-kpi.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(TargetKpiApprovementRequest $request, TargetKpi $target)
    {
        if (!$this->policy->approval($request->user(), $target)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $target) {

            $arrData = $request->validated();

            $target->update([
                "approval_status" => $arrData["approval_status"] ??
                    $target->approval_status
            ]);

            return $target;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.target-kpi.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Agency KPI Target Success!"
            ]);
    }
}
