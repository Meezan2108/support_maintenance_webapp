<?php

namespace App\Actions\ManagementFund;

use App\Models\Proposal;
use Carbon\Carbon;

class GenerateApplicationId
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return Proposal
     */
    public function execute(Proposal $proposal)
    {
        if ($proposal->application_id) return $proposal;

        $prefix = $proposal->proposal_type == Proposal::TYPE_TRF ? 'TRF' : 'EF';

        $yearMonth = date("Y-m");

        $number = $this->getLastId($prefix, $yearMonth);
        $number++;
        $strNumber = str_pad($number, 4, '0', STR_PAD_LEFT);

        $proposal->application_id = $prefix . '-' . $yearMonth . '-' . $strNumber;
        $proposal->submit_at = Carbon::now();

        $proposal->save();

        return $proposal;
    }

    public function getLastId(string $prefix, string $yearMonth)
    {
        $lastProposal = Proposal::query()
            ->where('application_id', 'LIKE', "$prefix-$yearMonth%")
            ->orderBy('application_id', 'DESC')
            ->first();

        if (!$lastProposal) return 0;

        $appId = $lastProposal->application_id;

        $curCounter = str_replace($prefix . "-" . $yearMonth . "-", "", $appId);

        return (int) ltrim($curCounter, '0');
    }
}
