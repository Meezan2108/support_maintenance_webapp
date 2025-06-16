<?php

namespace App\Http\Controllers\ManagementFund\SubForm;

use App\Actions\ManagementFund\CreateExpensesEstimation;
use App\Actions\ManagementFund\CreateProposalByRMC;
use App\Actions\ManagementFund\CreateProposalNotification;
use App\Actions\ManagementFund\CreateProposalTask;
use App\Actions\ManagementFund\GenerateApplicationId;
use App\Actions\ManagementFund\ValidateProposalData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form9Request;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Form9ProjectCostController extends Controller
{
    protected $trfPolicy;
    protected $externalPolicy;

    protected $createTask;
    protected $createExpensesEstimation;
    protected $createNotification;

    protected $user;

    public function __construct(
        ProposalTrfPolicy $trfPolicy,
        ProposalExternalFundPolicy $externalFundPolicy,
        CreateExpensesEstimation $createExpensesEstimation
    ) {
        $this->trfPolicy = $trfPolicy;
        $this->externalPolicy = $externalFundPolicy;

        $this->createExpensesEstimation = $createExpensesEstimation;
    }

    public function store(
        Form9Request $request,
        GenerateApplicationId $generateApplicationId
    ) {
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

        $proposal = DB::transaction(function () use (
            $request,
            $generateApplicationId,
            $isByRmc
        ) {
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

            if (isset($arrData["cost_salaried"]["years"])) {
                $arrCost = [
                    "years" => $arrData["years"],
                    "V11000" => [
                        [
                            "description" => "Salaried personal",
                            "years" => $arrData["cost_salaried"]["years"]
                        ]
                    ]
                ];
                $this->createExpensesEstimation->execute($proposal, $arrCost);
            }

            if ($request->save_as_draft == 1) {
                $proposal->approval_status = Approvement::STATUS_DRAFT;
                $proposal->save();

                $proposal = $generateApplicationId->execute($proposal);

                return $proposal;
            }

            if ($request->approval_status == 1) {
                $proposal->approval_status = $proposal->is_by_rmc
                    ? Proposal::STATUS_APPROVED
                    : Proposal::STATUS_SUBMITED;

                $user = User::find(Auth::id());

                if ($proposal->is_by_rmc) {
                    (new CreateProposalByRMC)->execute($proposal, $user);
                }

                $proposal->total_cost = $proposal->projectCost()
                    ->sum('cost');

                $proposal->save();

                $result = (new ValidateProposalData())->execute($proposal);
                if (!$result["status"]) {
                    throw ValidationException::withMessages([
                        'report' => "Please review your form, some data is incomplete in the following tab: "
                            . implode(", ", $result["tabs"])
                    ]);
                }

                $proposal = $generateApplicationId->execute($proposal);

                (new CreateProposalTask)->execute($proposal, $user);
                (new CreateProposalNotification)->execute($proposal);
            }

            return $proposal;
        });

        if ($proposal->is_by_rmc) {
            return redirect()->route("panel.list-of-approved.index")
                ->with("message", [
                    "status" => "success",
                    "message" => "Submit Proposal '$proposal->project_title' Success!"
                ]);
        }

        if (in_array($proposal->approval_status, [Approvement::STATUS_SUBMITED, Approvement::STATUS_DRAFT])) {
            $routeName = $proposal->proposal_type == Proposal::TYPE_TRF
                ? 'panel.trf.index'
                : 'panel.external-fund.index';

            return redirect()->route($routeName)
                ->with("message", [
                    "status" => "success",
                    "message" => "Submit Proposal '$proposal->project_title' Success!"
                ]);
        }

        return redirect()->back();
    }

    public function update(
        Form9Request $request,
        Proposal $form9,
        GenerateApplicationId $generateApplicationId
    ) {
        $policy = $request->proposal_type == Proposal::TYPE_TRF
            ? $this->trfPolicy
            : $this->externalPolicy;

        $user = User::find(Auth::id());

        if (!$policy->update($user, $form9)) {
            abort(403);
        }

        $proposal = DB::transaction(function () use (
            $request,
            $form9,
            $user,
            $generateApplicationId
        ) {
            $arrData = $request->validated();

            $proposal = $form9;

            if (isset($arrData["cost_salaried"]["years"])) {
                $arrCost = [
                    "years" => $arrData["years"],
                    "V11000" => [
                        [
                            "description" => "Salaried personal",
                            "years" => $arrData["cost_salaried"]["years"]
                        ]
                    ]
                ];
                $this->createExpensesEstimation->execute($proposal, $arrCost);
            }

            if ($request->save_as_draft == 1) {
                $proposal->approval_status = Approvement::STATUS_DRAFT;
                $proposal->save();

                $proposal = $generateApplicationId->execute($proposal);

                return $proposal;
            }

            if ($request->approval_status == 1) {
                $proposal->total_cost = $proposal->projectCost()
                    ->sum('cost');
                $proposal->approval_status = $proposal->approval_status == Approvement::STATUS_AMEND
                    ? Approvement::STATUS_RE_SUBMIT : Approvement::STATUS_SUBMITED;

                $proposal->approval_status = $proposal->is_by_rmc
                    ? Proposal::STATUS_APPROVED
                    : Proposal::STATUS_SUBMITED;

                $user = User::find(Auth::id());

                if ($proposal->is_by_rmc) {
                    (new CreateProposalByRMC)->execute($proposal, $user);
                }
                $proposal->save();

                (new CreateProposalTask)->execute($proposal, $user);
                (new CreateProposalNotification)->execute($proposal);
            }

            return $proposal;
        });

        if ($proposal->is_by_rmc) {
            return redirect()->route("panel.list-of-approved.index")
                ->with("message", [
                    "status" => "success",
                    "message" => "Submit Proposal '$proposal->project_title' Success!"
                ]);
        }

        if (in_array($proposal->approval_status, [
            Approvement::STATUS_RE_SUBMIT,
            Approvement::STATUS_SUBMITED,
            Approvement::STATUS_DRAFT
        ])) {

            $strStatus = Approvement::formatStatus($proposal->approval_status);

            $routeName = $proposal->proposal_type == Proposal::TYPE_TRF
                ? 'panel.trf.index'
                : 'panel.external-fund.index';
            return redirect()->route($routeName)
                ->with("message", [
                    "status" => "success",
                    "message" => $strStatus . " Proposal '$proposal->project_title' Success!"
                ]);
        }

        return redirect()->back();
    }
}
