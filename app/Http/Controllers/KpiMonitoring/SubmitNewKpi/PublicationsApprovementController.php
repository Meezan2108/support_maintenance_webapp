<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\CreateTask;
use App\Actions\KpiMonitoring\CreatePublicationApprovalNotification;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Helpers\CommentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\PublicationApprovementRequest;
use App\Models\Approvement;
use App\Models\Logable;
use App\Models\Publication;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\PublicationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PublicationsApprovementController extends Controller
{

    protected $policy;

    public function __construct(PublicationPolicy $policy)
    {
        $this->policy = $policy;
    }


    public function edit(Request $request, Publication $publication)
    {
        if (!$this->policy->approval($request->user(), $publication)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $publication->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Publications/Approvement', [
            "title" => "Publications Approval | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $publication->load("kpiAchievement.user", "type", "proposal", "researcherInvolved"),
                "file" => $file,
                "filters" => $filters,
                "urlApprovement" => route("panel.publications.approvement", $publication),
                "urlIndex" => route("panel.publications.index"),
                "optionsStatus" => CommentHelper::getOptionsStatus(),
            ]
        ]);
    }

    public function update(PublicationApprovementRequest $request, Publication $publication)
    {
        if (!$this->policy->approval($request->user(), $publication)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $publication) {

            $arrData = $request->validated();
            $kpiAchievement = $publication->kpiAchievement;

            $kpiAchievement->update([
                "comment" => $arrData["comment"] ?? "-",
                "approval_status" => $arrData["approval_status"] ??
                    $publication->kpiAchievement->approval_status
            ]);
            $publication->touch();

            $user = User::find(Auth::id());

            if (in_array($kpiAchievement->approval_status, [
                Approvement::STATUS_APPROVED,
                Approvement::STATUS_REJECTED
            ])) {
                $publication->taskable()->delete();
                (new ClearMyTaskCache)->execute($user->id);
            } else {
                (new CreateTask)->execute(
                    $publication,
                    $user,
                    $publication->user_id,
                    Taskable::TARGET_TYPE_USER,
                    route("panel.publications.edit", ["publication" => $publication->id]),
                    "-",
                    $publication->title,
                    'KPI Publications',
                    $kpiAchievement->approval_status
                );
            }

            (new CreatePublicationApprovalNotification)->execute(
                $publication,
                $user,
                $arrData["approval_status"] ??
                    $publication->kpiAchievement->approval_status
            );

            return $publication;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.publications.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Submit Approvment Status Publication Success!"
            ]);
    }
}
