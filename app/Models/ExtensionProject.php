<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtensionProject extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "extension_project";

    protected $guarded = ['created_at', 'updated_by', 'deleted_by'];

    const ARR_STATUS = [
        // 0 => 'Draft',
        1 => 'Submited',
        2 => 'Amend',
        3 => 'Resubmit',
        4 => 'Approved',
        5 => 'Rejected'
    ];

    const STATUS_DRAFT = 0;
    const STATUS_SUBMITED = 1;
    const STATUS_AMEND = 2;
    const STATUS_RE_SUBMIT = 3;
    const STATUS_APPROVED = 4;
    const STATUS_REJECTED = 5;

    const CHART_MILESTONE = 1;

    protected $casts = [
        'date_end_extension' => "date:Y-m-d"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function granttchart(): MorphMany
    {
        return $this->morphMany(Granttchartable::class, 'granttchartable');
    }

    public function approvement(): MorphMany
    {
        return $this->morphMany(Approvement::class, 'approvable');
    }

    public function approvementStep(): MorphOne
    {
        return $this->morphOne(ApprovementStep::class, 'approvementstepable');
    }

    public function taskable(): MorphOne
    {
        return $this->morphOne(Taskable::class, "taskable");
    }

    public function notifable(): MorphOne
    {
        return $this->morphOne(Notifable::class, "notifable");
    }

    public function formatStatus(int $status)
    {
        return self::ARR_STATUS[$status] ?? '';
    }
}
