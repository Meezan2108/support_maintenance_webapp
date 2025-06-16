<?php

namespace App\Http\Controllers\ManagementFund;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManagementFund\ShowResource;
use App\Models\Proposal;
use App\Models\RefProjectCostSeries;
use App\Models\RefProposalBenefitsItem;
use App\Policies\ProposalExternalFundPolicy;
use App\Policies\ProposalTrfPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Str;


class ProposalDownloadController extends Controller
{

    protected $policy;
    protected $policyTrf;
    protected $policyEf;

    public function __construct(ProposalTrfPolicy $policyTrf, ProposalExternalFundPolicy $policyEf)
    {
        $this->policyTrf = $policyTrf;
        $this->policyEf = $policyEf;
    }


    public function show(Request $request, Proposal $proposal)
    {
        $this->policy = $proposal->proposal_type == Proposal::TYPE_TRF
            ? $this->policyTrf : $this->policyEf;

        if (!$this->policy->view(Auth::user(), $proposal)) abort(403);

        $refBenefits = RefProposalBenefitsItem::query()
            ->orderBy("category", "ASC")
            ->orderBy("order", "ASC")
            ->get();

        $refProjectCostSeriesDirect = RefProjectCostSeries::query()
            ->orderBy("order", "ASC")
            ->whereIn("vseries_code", ["V21000", "V26000", "V28000", "V29000"])
            ->get();

        $pdf1 = Pdf::loadView("download-pdf.management-fund.proposal", [
            "proposal" => $proposal,
            "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration),
        ])->setOption(['dpi' => 110]);

        $pdf2 = Pdf::loadView("download-pdf.management-fund.proposal-landscape", [
            "proposal" => $proposal,
            "benefitsOutput" => $refBenefits->where("category", 1),
            "benefitsHuman" => $refBenefits->where("category", 2),
            "costSeries" => $refProjectCostSeriesDirect,
            "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration),
            "formatProposal" => (new ShowResource($proposal))->toArray($request)
        ])->setOption(['dpi' => 110])
            ->setPaper('A4', 'landscape');

        $pdf3 = Pdf::loadView("download-pdf.management-fund.proposal-part3", [
            "proposal" => $proposal,
            "benefitsOutput" => $refBenefits->where("category", 1),
            "benefitsHuman" => $refBenefits->where("category", 2),
            "costSeries" => $refProjectCostSeriesDirect,
            "arrYear" => DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration),
            "formatProposal" => (new ShowResource($proposal))->toArray($request)
        ])->setOption(['dpi' => 110]);


        $m = new Merger();

        $m->addRaw($pdf1->output());
        $m->addRaw($pdf2->output());
        $m->addRaw($pdf3->output());

        $nama_file = Str::slug('proposal-' . $proposal->application_id, '-') . '.pdf';

        return response($m->merge())
            ->withHeaders([
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'filename="' . $nama_file,
            ]);
    }
}
