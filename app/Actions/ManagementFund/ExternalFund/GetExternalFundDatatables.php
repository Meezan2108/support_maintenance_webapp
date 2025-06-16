<?php

namespace App\Actions\ManagementFund\ExternalFund;

use App\Actions\ManagementFund\GetDatatables;
use App\Models\Proposal;

class GetExternalFundDatatables extends GetDatatables
{
    public function __construct()
    {
        $this->setType(Proposal::TYPE_EXTERNAL_FUND);
        $this->setColumn();
    }
}
