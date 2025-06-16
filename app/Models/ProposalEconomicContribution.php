<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalEconomicContribution extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_economic_contribution";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
