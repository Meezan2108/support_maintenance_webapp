<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportMilestone extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "report_milestone";

    protected $guarded = ['created_at', 'updated_by', 'deleted_by'];

    const FILEABLE_REPORT_MILESTONE_CODE = "report_milestone";

    public function proposalMilestone(): BelongsTo
    {
        return $this->belongsTo(ProposalMilestone::class);
    }

    public function milestoneIpr(): HasMany
    {
        return $this->hasMany(MilestoneIpr::class, 'report_milestone_id');
    }

    public function milestoneExpertiseDevelopment(): HasMany
    {
        return $this->hasMany(MilestoneExpertiseDevelopment::class, 'report_milestone_id');
    }

    public function milestonePrototype(): HasMany
    {
        return $this->hasMany(MilestonePrototype::class, 'report_milestone_id');
    }

    public function milestonePublication(): HasMany
    {
        return $this->hasMany(MilestonePublication::class, 'report_milestone_id');
    }

    public function milestoneCommercialization(): HasMany
    {
        return $this->hasMany(MilestoneCommercialization::class, 'report_milestone_id');
    }

    public function approvement(): MorphMany
    {
        return $this->morphMany(Approvement::class, 'approvable');
    }


    public function fileable(): MorphMany
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
