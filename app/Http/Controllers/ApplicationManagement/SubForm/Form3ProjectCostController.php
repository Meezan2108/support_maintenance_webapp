<?php

namespace App\Http\Controllers\ApplicationManagement\SubForm;

use App\Actions\ManagementFund\CreateExpensesEstimation;
use App\Actions\MyTask\ClearMyTaskCache;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form9Request;
use App\Models\Proposal;
use App\Models\User;
use App\Policies\ListOfApprovedPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form3ProjectCostController extends Controller
{

    protected $policy;

    protected $user;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function update(Form9Request $request, Proposal $form3, CreateExpensesEstimation $createExpensesEstimation)
    {
        $policy = $this->policy;

        $user = User::find(Auth::id());

        if (!$policy->revision($user, $form3)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $form3, $createExpensesEstimation) {
            $arrData = $request->validated();

            $proposal = $form3;

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
                $createExpensesEstimation->execute($proposal, $arrCost);
            }

            $proposal->approved_cost = $proposal->projectCost()
                ->sum('cost');

            $proposal->save();

            $proposal->taskable()->delete();
            (new ClearMyTaskCache)->execute(Auth::id());

            return $proposal;
        });

        return redirect()->back();
    }
}
