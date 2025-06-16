<?php

namespace App\Http\Controllers\ManagementFund\SubForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form1Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form1IdentificationController extends Controller
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

    public function store(Form1Request $request)
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

        DB::transaction(function () use ($request, $isByRmc) {
            $arrData = $request->validated();
            $user = User::find(Auth::id());

            $researcherData = $arrData["researcher"];

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
                $arrData['ref_type_of_funding_id'] = $request->proposal_type == Proposal::TYPE_TRF
                    ? Proposal::TRF_ID : $request->ref_type_of_funding_id;

                $arrData['project_leader_name'] = $researcherData["name"];

                $arrData['approval_status'] = Approvement::STATUS_TEMP;
                $arrData["created_by"] = Auth::id();
                $proposal = Proposal::create($arrData);

                if ($isByRmc) {
                    $proposal->is_by_rmc = 1;
                    $proposal->save();
                }

                $proposal->researcher()->create($researcherData);
            } else {
                $arrData["updated_by"] = Auth::id();
                $proposal->update($arrData);

                $researcher = $proposal->researcher;
                if ($researcher) {
                    $researcher->update($researcherData);
                } else {
                    $proposal->researcher()->create($researcherData);
                }
            }

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

    public function update(Form1Request $request, Proposal $form1)
    {
        $policy = $request->proposal_type == 1
            ? $this->trfPolicy
            : $this->externalPolicy;

        if (!$policy->update($request->user(), $form1)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $form1) {
            $arrData = $request->validated();
            $researcherData = $arrData["researcher"];

            $proposal = $form1;

            $arrData['ref_type_of_funding_id'] = $request->proposal_type == 1
                ? Proposal::TRF_ID : $request->ref_type_of_funding_id;

            $proposal->update($arrData);

            $researcher = $proposal->researcher;

            if ($researcher) {
                $researcher->update($researcherData);
            } else {
                $proposal->researcher()->create($researcherData);
            }

            return $proposal;
        });

        return redirect()->back();
    }
}
