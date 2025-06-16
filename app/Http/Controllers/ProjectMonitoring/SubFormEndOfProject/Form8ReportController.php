<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\EndOfProject\CreateEndProjectSubmitNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form8Request;
use App\Models\Approvement;
use App\Models\Fileable;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class Form8ReportController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function store(Form8Request $request)
    {
        $arrData = $request->validated();
        $report = ReportEndProject::query()
            ->where('user_id', Auth::id())
            ->where('approval_status', Approvement::STATUS_DRAFT)
            ->first();

        if (!$report) {
            throw ValidationException::withMessages([
                'report' => "You need to choose project number at tab 1 (Project Details) before fill this form!"
            ]);
        }

        $proposal = $report->proposal;

        if (!$this->policy->create($request->user(), $proposal)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($report, $proposal, $isSubmited, $request, $arrData) {

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $report->fileable()->create(array_merge([
                        "code_type" => ReportEndProject::FILEABLE_DOC_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
            $report->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            $report->update([
                "approval_status" => $isSubmited
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            if ($isSubmited) {
                $user = User::find(Auth::id());

                (new CreateTask)->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.end-of-project.comment", ["report" => $report->id]),
                    $proposal->application_id,
                    "End Of Project: {$proposal->project_title}",
                    "EOP",
                    Approvement::STATUS_SUBMITED,
                    $proposal->researcher->division
                );

                (new CreateEndProjectSubmitNotification)->execute($report, $user);
            }

            return $report;
        });

        if ($isSubmited) {
            $reportName = $proposal->proposal_title;
            return redirect()->route('panel.end-of-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Create End Of Project Report '$reportName' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function update(Form8Request $request, ReportEndProject $form8)
    {
        $arrData = $request->validated();

        $report = $form8;

        $proposal = $report->proposal;

        if (!$this->policy->update($request->user(), $report)) abort(403);

        $isSubmited = $arrData["is_submited"] ?? false;

        $report = DB::transaction(function () use ($report, $proposal, $isSubmited, $request, $arrData) {

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $report->fileable()->create(array_merge([
                        "code_type" => ReportEndProject::FILEABLE_DOC_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"] ?? [])->pluck('id')->toArray();
            $report->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            $report->update([
                "approval_status" => $isSubmited
                    ? Approvement::STATUS_RE_SUBMIT
                    : $report->approval_status
            ]);

            if ($isSubmited) {
                $user = User::find(Auth::id());

                (new CreateTask)->execute(
                    $report,
                    $user,
                    User::ROLE_DIVISION_DIRECTOR,
                    "group",
                    route("panel.end-of-project.comment", ["report" => $report->id]),
                    $proposal->application_id,
                    "End Of Project: {$proposal->project_title}",
                    "EOP",
                    Approvement::STATUS_RE_SUBMIT,
                    $proposal->researcher->division
                );

                (new CreateEndProjectSubmitNotification)->execute($report, $user);
            }

            return $report;
        });

        if ($isSubmited) {
            $reportName = $proposal->proposal_title;
            return redirect()->route('panel.end-of-project.index')
                ->with("message", [
                    "status" => "success",
                    "message" => "Update End Of Project Report '$reportName' Success!"
                ]);
        }

        return redirect()->back();
    }
}
