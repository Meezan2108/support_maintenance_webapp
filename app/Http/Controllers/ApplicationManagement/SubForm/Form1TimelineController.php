<?php

namespace App\Http\Controllers\ApplicationManagement\SubForm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationManagement\Form1Request;
use App\Models\Proposal;
use App\Policies\ListOfApprovedPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Form1TimelineController extends Controller
{

    protected $policy;

    protected $user;

    public function __construct(ListOfApprovedPolicy $policy)
    {
        $this->policy = $policy;
        $this->user = Auth::user();
    }

    public function update(Form1Request $request, Proposal $form1)
    {
        $policy = $this->policy;

        if (!$policy->revision($request->user(), $form1)) abort(403);

        $proposal = DB::transaction(function () use ($request, $form1) {
            $arrData = $request->validated();

            $proposal = $form1;

            $startDate = $arrData["schedule_start_date"] . '-01';
            $proposal->update([
                "ptj_code" => $arrData["ptj_code"] ?? [],
                "project_number" => $arrData["project_number"] ?? "",
                "schedule_start_date" => $startDate,
                "schedule_duration" => $arrData["schedule_duration"],
                "rmc_id" => Auth::id()
            ]);

            return $proposal;
        });


        return redirect()->back();
    }
}
