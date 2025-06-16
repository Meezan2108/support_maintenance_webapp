<?php

namespace App\Http\Controllers\ManagementFund\SubForm;

use App\Actions\ManagementFund\CreateExpensesEstimation;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form8Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form8ExpensesEstimationController extends Controller
{

    protected $trfPolicy;
    protected $externalPolicy;

    protected $user;

    public function __construct(ProposalTrfPolicy $trfPolicy, ProposalExternalFundPolicy $externalFundPolicy)
    {
        $this->trfPolicy = $trfPolicy;
        $this->externalPolicy = $externalFundPolicy;
    }

    public function store(Form8Request $request, CreateExpensesEstimation $createExpensesEstimation)
    {
        $policy = $request->proposal_type == Proposal::TYPE_TRF
            ? $this->trfPolicy
            : $this->externalPolicy;

        $isByRmc = $request->by_rmc == 1;
        $policy = $isByRmc
            ? new ListOfApprovedPolicy()
            : $policy;

        if (!$policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $createExpensesEstimation, $isByRmc) {
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

            $createExpensesEstimation->execute($proposal, $arrData);

            return $proposal;
        });

        return redirect()->back();
    }

    public function show(Request $request, Proposal $form1)
    {
        $this->authorize('view', $form1);

        return response([
            'proposal' => $form1
        ], 200);
    }

    public function update(Form8Request $request, Proposal $form8, CreateExpensesEstimation $createExpensesEstimation)
    {
        $policy = $request->proposal_type == Proposal::TYPE_TRF
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $form8, $createExpensesEstimation) {
            $arrData = $request->validated();

            $proposal = $form8;

            return $createExpensesEstimation->execute($proposal, $arrData);
        });

        return redirect()->back();
    }
}
