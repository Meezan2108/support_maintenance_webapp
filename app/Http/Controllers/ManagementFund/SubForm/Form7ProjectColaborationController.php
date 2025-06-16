<?php

namespace App\Http\Controllers\ManagementFund\SubForm;

use App\Actions\ManagementFund\CreateProjectCollaboration;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form7Request;
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

class Form7ProjectColaborationController extends Controller
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

    public function store(Form7Request $request, CreateProjectCollaboration $createProjectCollaboration)
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

        DB::transaction(function () use ($request, $createProjectCollaboration, $isByRmc) {
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

            return $createProjectCollaboration->execute($proposal, $arrData);
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

    public function update(
        Form7Request $request,
        Proposal $form7,
        CreateProjectCollaboration $createProjectCollaboration
    ) {
        $policy = $request->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $form7, $createProjectCollaboration) {
            $arrData = $request->validated();

            $proposal = $form7;

            return $createProjectCollaboration->execute($proposal, $arrData);
        });

        return redirect()->back();
    }
}
