<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProposalProjectCostDetail extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_project_cost_detail";

    protected $guarded = ["id"];

    public $timestamps = false;

    public function projectCost(): BelongsTo
    {
        return $this->belongsTo(ProposalProjectCost::class);
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
