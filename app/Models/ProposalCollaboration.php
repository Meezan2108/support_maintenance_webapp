<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalCollaboration extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_collaboration";

    const TYPE_ORGANISATIONS = 1;
    const TYPE_INDUSTRIES = 2;

    protected $guarded = ["id"];

    public $timestamps = false;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
