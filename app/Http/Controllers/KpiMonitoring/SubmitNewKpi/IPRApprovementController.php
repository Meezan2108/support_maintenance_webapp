<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateIPRApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\IPRApprovementRequest;
use App\Models\Approvement;
use App\Models\IPR;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\IPRPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IPRApprovementController extends Controller
{

    protected $policy;

    public function __construct(IPRPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, IPR $ipr)
    {
        if (!$this->policy->approval($request->user(), $ipr)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $ipr->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/IPR/Approvement', [
            "title" => "Intellectual Property Right Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $ipr->load("kpiAchievement.user", "patent", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.ipr.approvement", $ipr),
                "urlIndex" => route("panel.ipr.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(IPRApprovementRequest $request, IPR $ipr)
    {
        if (!$this->policy->approval($request->user(), $ipr)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $ipr) {

            $arrData = $request->validated();

            $ipr->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $ipr->kpiAchievement->approval_status
            ]);
            $ipr->touch();

            $user = User::find(Auth::id());

            $ipr->load("kpiAchievement");
            $kpiAchievement = $ipr->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $ipr->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $ipr,
                    $user,
                    $ipr->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.ipr.edit", ["ipr" => $ipr->id]),
                    "-",
                    $ipr->output,
                    'KPI IPR',
                    $arrData["approval_status"] ??
                        $ipr->kpiAchievement->approval_status
                );
            }

            (new CreateIPRApprovalNotification)->execute(
                $ipr,
                $user,
                $arrData["approval_status"] ??
                    $ipr->kpiAchievement->approval_status
            );

            return $ipr;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ipr.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Intellectual Property Right Success!"
            ]);
    }
}
