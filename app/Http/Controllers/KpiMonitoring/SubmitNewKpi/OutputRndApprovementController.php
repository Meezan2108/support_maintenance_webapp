<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateOutputRndApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\OutputRndApprovementRequest;
use App\Models\Approvement;
use App\Models\OutputRnd;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\OutputRnDPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OutputRndApprovementController extends Controller
{

    protected $policy;

    public function __construct(OutputRnDPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->approval($request->user(), $outputrnd)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $outputrnd->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/OutputRnd/Approvement', [
            "title" => "R&D Output Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $outputrnd->load("kpiAchievement.user", "output_type", "output_status", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.rnd-output.approvement", $outputrnd),
                "urlIndex" => route("panel.rnd-output.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(OutputRndApprovementRequest $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->approval($request->user(), $outputrnd)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $outputrnd) {

            $arrData = $request->validated();

            $outputrnd->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $outputrnd->kpiAchievement->approval_status
            ]);
            $outputrnd->touch();

            $user = User::find(Auth::id());

            $outputrnd->load("kpiAchievement");
            $kpiAchievement = $outputrnd->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $outputrnd->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $outputrnd,
                    $user,
                    $outputrnd->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.rnd-output.edit", ["outputrnd" => $outputrnd->id]),
                    "-",
                    $outputrnd->output,
                    'KPI R&D Output',
                    $arrData["approval_status"] ??
                        $outputrnd->kpiAchievement->approval_status
                );
            }

            (new CreateOutputRndApprovalNotification)->execute(
                $outputrnd,
                $user,
                $arrData["approval_status"] ??
                    $outputrnd->kpiAchievement->approval_status
            );

            return $outputrnd;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.rnd-output.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status R&D Output Success!"
            ]);
    }
}
