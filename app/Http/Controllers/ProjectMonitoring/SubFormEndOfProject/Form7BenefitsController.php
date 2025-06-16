<?php

namespace App\Http\Controllers\ProjectMonitoring\SubFormEndOfProject;

use App\Actions\CreateTask;
use App\Actions\ProjectMonitoring\EndOfProject\CreateEopBenefits;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMonitoring\EndOfProject\Form7Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ReportEndProject;
use App\Models\ReportQuarterly;
use App\Models\User;
use App\Policies\EndOfProjectPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form7BenefitsController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(EndOfProjectPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function store(Form7Request $request)
    {
        $arrData = $request->validated();
        $userAuth = User::find(Auth::id());

        $report = ReportEndProject::query()
            ->where('user_id', $userAuth->id)
            ->where('approval_status', Approvement::STATUS_DRAFT)
            ->first();

        if (!$report) {
            throw ValidationException::withMessages([
                'report' => "You need to choose project number at tab 1 (Project Details) before fill this form!"
            ]);
        }

        $proposal = $report->proposal;

        if (!$this->policy->create($request->user(), $proposal)) abort(403);

        $report = DB::transaction(function () use ($report, $userAuth, $arrData, $request) {
            return (new CreateEopBenefits)->execute($report, $userAuth, $arrData['answers'] ?? []);
        });

        return redirect()->back();
    }

    public function update(Form7Request $request, ReportEndProject $form7)
    {

        $arrData = $request->validated();
        $userAuth = User::find(Auth::id());

        $report = $form7;

        if (!$this->policy->update($request->user(), $report)) abort(403);

        $report = DB::transaction(function () use ($report, $userAuth, $request) {
            return (new CreateEopBenefits)->execute($report, $userAuth, $request->answers);
        });

        return redirect()->back();
    }
}
