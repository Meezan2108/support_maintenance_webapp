<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateRecognitionApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\RecognitionApprovementRequest;
use App\Models\Approvement;
use App\Models\Recognition;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\RecognitionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RecognitionApprovementController extends Controller
{

    protected $policy;

    public function __construct(RecognitionPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, Recognition $recognition)
    {
        if (!$this->policy->approval($request->user(), $recognition)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        $recognition["recognition_type"] = $recognition::ARR_TYPE[$recognition->type];

        $file = $recognition->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Recognition/Approvement', [
            "title" => "Recogntions Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $recognition->load("kpiAchievement.user", "fileable", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.recognition.approvement", $recognition),
                "urlIndex" => route("panel.recognition.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(RecognitionApprovementRequest $request, Recognition $recognition)
    {
        if (!$this->policy->approval($request->user(), $recognition)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $recognition) {

            $arrData = $request->validated();

            $recognition->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $recognition->kpiAchievement->approval_status
            ]);
            $recognition->touch();

            $user = User::find(Auth::id());

            $recognition->load("kpiAchievement");
            $kpiAchievement = $recognition->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $recognition->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $recognition,
                    $user,
                    $recognition->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.recognition.edit", ["recognition" => $recognition->id]),
                    "-",
                    $recognition->recognition,
                    'KPI Recognitions',
                    $arrData["approval_status"] ??
                        $recognition->kpiAchievement->approval_status
                );
            }

            (new CreateRecognitionApprovalNotification)->execute(
                $recognition,
                $user,
                $arrData["approval_status"] ??
                    $recognition->kpiAchievement->approval_status
            );

            return $recognition;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.recognition.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Recognition Success!"
            ]);
    }
}
