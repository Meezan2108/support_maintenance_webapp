<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreateImportedGermplasmApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\ImportedGermplasmApprovementRequest;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\IPR;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\ImportedGermplasmPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ImportedGermplasmApprovementController extends Controller
{

    protected $policy;

    public function __construct(ImportedGermplasmPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->approval($request->user(), $germplasm)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $germplasm->fileable->each(
            fn ($file) => $file->file_url = $file->url
        );

        return Inertia::render('KpiMonitoring/SubmitNewKpi/ImportedGermplasm/Approvement', [
            "title" => "Imported Germplasm Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $germplasm->load("kpiAchievement.user", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.imported-germplasm.approvement", $germplasm),
                "urlIndex" => route("panel.imported-germplasm.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(ImportedGermplasmApprovementRequest $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->approval($request->user(), $germplasm)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $germplasm) {

            $arrData = $request->validated();

            $germplasm->kpiAchievement()->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $germplasm->kpiAchievement->approval_status
            ]);
            $germplasm->touch();

            $user = User::find(Auth::id());

            $germplasm->load("kpiAchievement");
            $kpiAchievement = $germplasm->kpiAchievement;
            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED, Approvement::STATUS_REJECTED
            ])) {
                $germplasm->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $germplasm,
                    $user,
                    $germplasm->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.imported-germplasm.edit", ["germplasm" => $germplasm->id]),
                    "-",
                    Carbon::parse($germplasm->date)->format("Y-m-d"),
                    'KPI Imported Germplasm',
                    $arrData["approval_status"] ??
                        $germplasm->kpiAchievement->approval_status
                );
            }

            (new CreateImportedGermplasmApprovalNotification)->execute(
                $germplasm,
                $user,
                $arrData["approval_status"] ??
                    $germplasm->kpiAchievement->approval_status
            );

            return $germplasm;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.imported-germplasm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Imported Germplasm Success!"
            ]);
    }
}
