<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalResearcher extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_researcher";

    protected $guarded = [];

    public $timestamps = false;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, "ref_position_id");
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, "ref_division_id");
    }
}
