<?php

namespace App\Http\Controllers\ApplicationManagement\SubForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\Form4Request;
use App\Models\Fileable;
use App\Models\Proposal;
use App\Policies\ListOfApprovedPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Form5StatusController extends Controller
{
    protected $policy;

    protected $user;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function update(Request $request, Proposal $form5)
    {
        $request->validate([
            "status" => ['required', Rule::in(array_keys(Proposal::ARR_STATUS_PROJECT))]
        ]);

        if (!$this->policy->revision($request->user(), $form5)) {
            abort(403);
        }

        $proposal = $form5;

        $proposal = DB::transaction(function () use ($proposal, $request) {

            $proposal->project_status = $request->status;

            if ($request->status == Proposal::STATUS_PRJ_ON_GOING) {
                $proposal->rmc_id = Auth::id();
                $proposal->approval_at = Carbon::now();
                $proposal->rmc_proceed_at = Carbon::now();
            }

            $proposal->save();

            return $proposal;
        });

        $proposalTitle = $proposal->proposal_title;
        return redirect()->route("panel.list-of-approved.index")
            ->with("message", [
                "status" => "success",
                "message" => "Update Approved '$proposalTitle' Success!"
            ]);
    }
}
