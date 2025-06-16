<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateCommercializationApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\CommercializationApprovementRequest;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\CommercializationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommercializationApprovementController extends Controller
{

    protected $policy;

    public function __construct(CommercializationPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, Commercialization $commercialization)
    {
        if (!$this->policy->approval($request->user(), $commercialization)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $commercialization->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Commercialization/Approvement', [
            "title" => "Commercialization Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $commercialization->load("kpiAchievement.user", "output_type", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.commercialization.approvement", $commercialization),
                "urlIndex" => route("panel.commercialization.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(CommercializationApprovementRequest $request, Commercialization $commercialization)
    {
        if (!$this->policy->approval($request->user(), $commercialization)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $commercialization) {

            $arrData = $request->validated();

            $commercialization->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $commercialization->kpiAchievement->approval_status
            ]);
            $commercialization->touch();

            $user = User::find(Auth::id());

            $commercialization->load("kpiAchievement");
            $kpiAchievement = $commercialization->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $commercialization->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $commercialization,
                    $user,
                    $commercialization->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.commercialization.edit", ["commercialization" => $commercialization->id]),
                    "-",
                    $commercialization->name,
                    'KPI Commercialization',
                    $arrData["approval_status"] ??
                        $commercialization->kpiAchievement->approval_status
                );
            }

            (new CreateCommercializationApprovalNotification)->execute(
                $commercialization,
                $user,
                $arrData["approval_status"] ??
                    $commercialization->kpiAchievement->approval_status
            );

            return $commercialization;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.commercialization.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Commercialization Success!"
            ]);
    }
}
