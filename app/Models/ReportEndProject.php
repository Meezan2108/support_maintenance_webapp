<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportEndProject extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "report_end_project";

    protected $guarded = ['created_at', 'updated_by', 'deleted_by'];

    const FILEABLE_DOC_CODE = "document-eop";

    const ARR_STATUS = [
        0 => 'Draft',
        1 => 'Submitted',
        2 => 'Amend',
        3 => 'Resubmit',
        4 => 'Completed',
        5 => 'Rejected'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function approvement(): MorphMany
    {
        return $this->morphMany(Approvement::class, 'approvable');
    }
    public function approvementStep(): MorphOne
    {
        return $this->morphOne(ApprovementStep::class, 'approvementstepable');
    }

    public function fileable(): MorphMany
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function taskable(): MorphOne
    {
        return $this->morphOne(Taskable::class, "taskable");
    }

    public function notifable(): MorphOne
    {
        return $this->morphOne(Notifable::class, "notifable");
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }

    public function benefitAnswer(): HasMany
    {
        return $this->hasMany(ReportEopBenefit::class, 'report_end_project_id');
    }

    public function objective(): MorphMany
    {
        return $this->morphMany(Objectiveable::class, "objectiveable");
    }
}
