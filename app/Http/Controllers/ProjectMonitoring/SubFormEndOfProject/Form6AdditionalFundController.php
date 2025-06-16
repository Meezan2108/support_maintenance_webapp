<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject;

use App\Actions\CreateTask;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form6Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form6AdditionalFundController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function store(Form6Request $request)
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

            $report->update([
                "additional_fund" => $arrData["additional_fund"] ?? "",
            ]);

            return $report;
        });

        return redirect()->back();
    }

    public function update(Form6Request $request, ReportEndProject $form6)
    {
        $arrData = $request->validated();

        $report = $form6;

        if (!$this->policy->update($request->user(), $report)) abort(403);

        $report = DB::transaction(function () use ($report, $arrData) {

            $report->update([
                "additional_fund" => $arrData["additional_fund"] ?? "",
            ]);

            return $report;
        });

        return redirect()->back();
    }
}
