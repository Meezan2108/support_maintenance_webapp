<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\AnalyticalServiceLabApprovementRequest;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\AnalyticalServiceLabPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticalServiceLabApprovementController extends Controller
{

    protected $policy;

    public function __construct(AnalyticalServiceLabPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->approval($request->user(), $analytical)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $analytical->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/AnalyticalServiceLab/Approvement', [
            "title" => "Analytical Service Lab Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $analytical->load("kpiAchievement.user", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.analytical-service-lab.approvement", $analytical),
                "urlIndex" => route("panel.analytical-service-lab.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(AnalyticalServiceLabApprovementRequest $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->approval($request->user(), $analytical)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $analytical) {

            $arrData = $request->validated();

            $analytical->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $analytical->kpiAchievement->approval_status
            ]);
            $analytical->touch();

            $user = User::find(Auth::id());

            $analytical->load("kpiAchievement");
            $kpiAchievement = $analytical->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $analytical->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $analytical,
                    $user,
                    $analytical->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.analytical-service-lab.edit", ["analytical" => $analytical->id]),
                    "-",
                    $analytical->date,
                    'KPI Analytical Service Lab',
                    $arrData["approval_status"] ??
                        $analytical->kpiAchievement->approval_status
                );
            }

            (new CreateAnalyticalServiceLabApprovalNotification)->execute(
                $analytical,
                $user,
                $arrData["approval_status"] ??
                    $analytical->kpiAchievement->approval_status
            );

            return $analytical;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.analytical-service-lab.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Analytical Service Lab Success!"
            ]);
    }
}
