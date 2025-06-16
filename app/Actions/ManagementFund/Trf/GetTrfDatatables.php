<?php

namespace App\Actions\ManagementFund\Trf;

use App\Actions\ManagementFund\GetDatatables;
use App\Models\Proposal;

class GetTrfDatatables extends GetDatatables
{
    public function __construct()
    {
        $this->setType(Proposal::TYPE_TRF);
        $this->setColumn();
    }
}
