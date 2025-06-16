<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportQuarterly extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "report_quarterly";

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    const ARR_TYPE = [
        1 => "MAR",
        2 => "QFR"
    ];

    const TYPE_MAR = 1;
    const TYPE_QFR = 2;

    const TYPE_TRF = 1;
    const TYPE_EXTERNAL_FUND = 2;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function reportMilestone(): HasOne
    {
        return $this->hasOne(ReportMilestone::class, 'report_quarterly_id');
    }

    public function reportQuarterlyFinancial(): HasOne
    {
        return $this->hasOne(ReportQuarterlyFinancial::class, 'report_quarterly_id');
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

    public function formatType(int $type)
    {
        return self::ARR_TYPE[$type] ?? '';
    }
}
