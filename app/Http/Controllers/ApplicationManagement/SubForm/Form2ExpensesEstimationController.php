<?php

namespace App\Http\Controllers\ApplicationManagement\SubForm;

use App\Actions\ManagementFund\CreateExpensesEstimation;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagementFund\Form8Request;
use App\Models\Proposal;
use App\Policies\ListOfApprovedPolicy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form2ExpensesEstimationController extends Controller
{

    protected $policy;

    protected $user;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function update(Form8Request $request, Proposal $form2, CreateExpensesEstimation $createExpensesEstimation)
    {
        $policy = $this->policy;

        if (!$policy->create($request->user())) abort(403);

        $proposal = DB::transaction(function () use ($request, $form2, $createExpensesEstimation) {
            $arrData = $request->validated();

            $proposal = $form2;

            return $createExpensesEstimation->execute($proposal, $arrData);
        });

        return redirect()->back();
    }
}
