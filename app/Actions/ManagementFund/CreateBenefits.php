<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;

class CreateBenefits
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal, array $arrData)
    {
        foreach ($arrData["output_expected"] as $item) {
            $benefit = $proposal->benefits()
                ->where("ref_proposal_benefits_category_id", $item["ref_proposal_benefits_category_id"])
                ->first();

            if ($benefit) {
                $benefit->update([
                    "detail" => $item["detail"],
                    "quantity" => $item["quantity"]
                ]);
            } else {
                $proposal->benefits()->create([
                    "ref_proposal_benefits_category_id" => $item["ref_proposal_benefits_category_id"],
                    "detail" => $item["detail"],
                    "quantity" => $item["quantity"]
                ]);
            }
        }

        foreach ($arrData["human_capital"] as $item) {
            $benefit = $proposal->benefits()
                ->where("ref_proposal_benefits_category_id", $item["ref_proposal_benefits_category_id"])
                ->first();

            if ($benefit) {
                $benefit->update([
                    "detail" => $item["detail"],
                    "quantity" => $item["quantity"]
                ]);
            } else {
                $proposal->benefits()->create([
                    "ref_proposal_benefits_category_id" => $item["ref_proposal_benefits_category_id"],
                    "detail" => $item["detail"],
                    "quantity" => $item["quantity"]
                ]);
            }
        }


        $ecnomicContributions = $arrData["economic_contributions"] ?? [];
        $ecnomicContributionsId = [];
        foreach ($ecnomicContributions as $key => $item) {
            $ecnomicContribution = null;
            if ($item["id"] ?? false) {
                $ecnomicContribution = $proposal->economicContribution()
                    ->where("id", $item["id"])
                    ->first();
            }

            $item["order"] = $key + 1;

            if ($ecnomicContribution) {
                $ecnomicContribution->update($item);
                $ecnomicContributionsId[] = $ecnomicContribution->id;
            } else {
                $ecnomicContribution = $proposal->economicContribution()
                    ->create($item);
                $ecnomicContributionsId[] = $ecnomicContribution->id;
            }
        }

        $proposal->economicContribution()
            ->whereNotIn('id', $ecnomicContributionsId)
            ->delete();

        // $proposal->update([
        //     "economic_contributions" => $arrData["economic_contributions"] ?? ''
        // ]);


        return $proposal;
    }
}
