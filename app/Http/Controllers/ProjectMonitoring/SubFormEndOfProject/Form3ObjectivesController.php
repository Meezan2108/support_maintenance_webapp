<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\EndOfProject\CreateObjectives;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form3Request;
use App\Models\Approvement;
use App\Models\Objectiveable;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form3ObjectivesController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function store(Form3Request $request)
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

        $report = DB::transaction(function () use ($report, $arrData) {

            $report = (new CreateObjectives)->execute(
                $report,
                $arrData["objectives_achieved"] ?? [],
                Objectiveable::STATUS_ACHIEVED
            );

            $report = (new CreateObjectives)->execute(
                $report,
                $arrData["objectives_not_achieved"] ?? [],
                Objectiveable::STATUS_NOT_ACHIEVED
            );

            return $report;
        });

        return redirect()->back();
    }

    public function update(Form3Request $request, ReportEndProject $form3)
    {
        $arrData = $request->validated();

        $report = $form3;

        if (!$this->policy->update($request->user(), $report)) abort(403);

        $report = DB::transaction(function () use ($report, $arrData) {

            $report = (new CreateObjectives)->execute(
                $report,
                $arrData["objectives_achieved"] ?? [],
                Objectiveable::STATUS_ACHIEVED
            );

            $report = (new CreateObjectives)->execute(
                $report,
                $arrData["objectives_not_achieved"] ?? [],
                Objectiveable::STATUS_NOT_ACHIEVED
            );

            return $report;
        });

        return redirect()->back();
    }
}
