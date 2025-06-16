<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject;

use App\Actions\CreateTask;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form1Request;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form3Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form1ProjectDetailsController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function store(Form1Request $request)
    {
        $arrData = $request->validated();

        $report = ReportEndProject::query()
            ->where('user_id', Auth::id())
            ->where('approval_status', Approvement::STATUS_DRAFT)
            ->first();

        $proposal = Proposal::find($arrData["proposal_id"]);

        if (!$this->policy->create($request->user(), $proposal)) abort(403);

        $report = DB::transaction(function () use ($report, $proposal) {

            $arrInsert = [
                "proposal_id" => $proposal->id,
                "user_id" => Auth::id(),
                "approval_status" => Approvement::STATUS_DRAFT
            ];

            if (!$report) {
                $report = ReportEndProject::create($arrInsert);
            } else {
                $report->update($arrInsert);
            }

            return $report;
        });

        return redirect()->back();
    }

    public function update(Form3Request $request, ReportEndProject $form1)
    {
        $arrData = $request->validated();

        $report = $form1;

        $proposal = $report->proposal;

        if (!$this->policy->update($request->user(), $report)) abort(403);

        $report = DB::transaction(function () use ($report, $proposal) {

            return $report;
        });


        return redirect()->back();
    }
}
