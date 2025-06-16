<?php

namespace App\Http\Controllers\ManagementFund\SubForm;

use App\Actions\ManagementFund\CreateObjectives;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form2Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form2ObjectivesController extends Controller
{

    protected $trfPolicy;
    protected $externalPolicy;

    protected $user;

    public function __construct(ProposalTrfPolicy $trfPolicy, ProposalExternalFundPolicy $externalFundPolicy)
    {
        $this->trfPolicy = $trfPolicy;
        $this->externalPolicy = $externalFundPolicy;
        $this->user = Auth::user();
    }

    public function store(Form2Request $request, CreateObjectives $createObjectives)
    {
        $policy = $request->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        $isByRmc = $request->by_rmc == 1;
        $policy = $isByRmc
            ? new ListOfApprovedPolicy()
            : $policy;

        if (!$policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $createObjectives, $isByRmc) {
            $arrData = $request->validated();
            $user = User::find(Auth::id());

            $proposal = Proposal::where('approval_status', Approvement::STATUS_TEMP)
                ->where("proposal_type", $request->proposal_type)
                ->when($isByRmc, function ($query) {
                    return $query->rmcProposal(Auth::id());
                })
                ->when($user->hasRole("Researcher"), function ($query) use ($user) {
                    return $query->where("user_id", $user->id);
                })
                ->first();


            if (!$proposal) {
                throw ValidationException::withMessages([
                    'report' => "You need to fill the tab 1 (Project Identification) before fill this form!"
                ]);
            }

            return $createObjectives->execute($proposal, $arrData);
        });

        return redirect()->back();
    }

    public function update(Form2Request $request, Proposal $form2, CreateObjectives $createObjectives)
    {
        $policy = $request->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $form2, $createObjectives) {
            $arrData = $request->validated();

            $proposal = $form2;

            return $createObjectives->execute($proposal, $arrData);
        });

        return redirect()->back();
    }
}
