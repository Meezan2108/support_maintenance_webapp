<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalResearchField extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_research_field";

    protected $guarded = ["id"];
    public $timestamps = false;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefForCategory::class, "ref_for_category_id");
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(RefForGroup::class, "ref_for_group_id");
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(RefForArea::class, "ref_for_area_id");
    }
}
