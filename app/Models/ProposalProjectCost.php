<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProposalProjectCost extends Model
{
    use HasFactory, HasLog;

    protected $table = "proposal_project_cost";

    protected $guarded = ["id"];

    public $timestamps = false;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function costSeries(): BelongsTo
    {
        return $this->belongsTo(RefProjectCostSeries::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(ProposalProjectCostDetail::class);
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
