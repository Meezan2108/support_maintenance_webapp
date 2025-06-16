<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefProposalBenefitsItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_proposal_benefits_item";

    protected $guarded = [];

    public function proposalBenefis(): HasMany
    {
        return $this->hasMany(ProposalBenefits::class);
    }
}
