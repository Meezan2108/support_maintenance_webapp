<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalBenefits extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_benefits";

    protected $guarded = [];

    public $timestamps = false;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(RefProposalBenefitsItem::class, "ref_proposal_benefits_category_id");
    }

    public function scopeOutputExpected($query)
    {
        return $query->whereHas("item", function ($query) {
            return $query->where("category", 1);
        });
    }

    public function scopeHumanCapital($query)
    {
        return $query->whereHas("item", function ($query) {
            return $query->where("category", 2);
        });
    }
}
