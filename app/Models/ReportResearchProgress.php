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

class ReportResearchProgress extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "report_research_progress";

    protected $guarded = ['created_at', 'updated_by', 'deleted_by'];

    const FILEABLE_DOC_CODE = "research_progress_file";

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

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, 'ref_division_id');
    }

    public function reportType(): BelongsTo
    {
        return $this->belongsTo(RefReportType::class, "ref_report_type_id");
    }

    public function pslkm(): BelongsTo
    {
        return $this->belongsTo(RefPslkm::class, "ref_pslkm_id");
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

    public function fileable(): MorphMany
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function projectTeam(): MorphMany
    {
        return $this->morphMany(ProjectTeamable::class, 'project_teamable');
    }

    public function objective(): MorphMany
    {
        return $this->morphMany(Objectiveable::class, 'objectiveable');
    }
}
