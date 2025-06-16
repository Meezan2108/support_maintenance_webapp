<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    const ARR_STATUS = [
        // 0 => 'Draft',
        1 => 'Submitted',
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

    const TYPE_TRF = 1;
    const TYPE_EXTERNAL_FUND = 2;

    const TRF_ID = 6;

    const ARR_STATUS_PROJECT = [
        0 => 'Draft',
        1 => 'Approved',
        2 => 'On-Going',
        3 => 'Completed',
        4 => 'Extended',
        5 => 'Terminated',
    ];

    const STATUS_PRJ_PROPOSAL = 1;
    const STATUS_PRJ_ON_GOING = 2;
    const STATUS_PRJ_COMPLETED = 3;
    const STATUS_PRJ_EXTENDED = 4;
    const STATUS_PRJ_TERMINATED = 5;

    const TYPE_LEADER_INTERNAL = 1;
    const TYPE_LEADER_EXTERNAL = 2;

    const FILEABLE_DOCUMENTATION_CODE = "documentation";

    protected $table = "proposal";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'options' => 'array',
        'keywords' => 'array',
        'ptj_code' => 'array',
        "schedule_start_date" => "date:Y-m",
        'original_data' => 'array'
    ];

    /** START REF TABLE **/
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeOfFund(): BelongsTo
    {
        return $this->belongsTo(RefTypeOfFunding::class, 'ref_type_of_funding_id');
    }

    public function researchType(): BelongsTo
    {
        return $this->belongsTo(RefResearchType::class, 'ref_research_type_id');
    }

    public function researchCluster(): BelongsTo
    {
        return $this->belongsTo(RefResearchCluster::class, 'ref_research_cluster_id');
    }

    public function seoCategory(): BelongsTo
    {
        return $this->belongsTo(RefSeoCategory::class, 'ref_seo_category_id');
    }

    public function seoGroup(): BelongsTo
    {
        return $this->belongsTo(RefSeoGroup::class, 'ref_seo_group_id');
    }

    public function seoArea(): BelongsTo
    {
        return $this->belongsTo(RefSeoArea::class, 'ref_seo_area_id');
    }
    /** END REF TABLE **/

    /** START DETAIL TABLE **/
    public function researcher(): HasOne
    {
        return $this->hasOne(ProposalResearcher::class);
    }

    public function collaborations(): HasMany
    {
        return $this->hasMany(ProposalCollaboration::class);
    }

    public function primaryResearchField(): HasOne
    {
        return $this->hasOne(ProposalResearchField::class, 'proposal_id')
            ->where('type', 1);
    }

    public function secondaryResearchField(): HasOne
    {
        return $this->hasOne(ProposalResearchField::class, 'proposal_id')
            ->where('type', 2);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProposalMilestone::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProposalProjectActivities::class);
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(ProposalObjectives::class);
    }

    public function economicContribution(): HasMany
    {
        return $this->hasMany(ProposalEconomicContribution::class);
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
        return $this->morphMany(Fileable::class, "fileable");
    }

    public function reminderSendLog(): MorphMany
    {
        return $this->morphMany(ReminderSendLog::class, "ref");
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(ProposalBenefits::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(ProposalProjectTeam::class);
    }

    public function projectCost(): HasMany
    {
        return $this->hasMany(ProposalProjectCost::class);
    }

    public function evaluation(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function reportQuarterly(): HasMany
    {
        return $this->hasMany(ReportQuarterly::class);
    }

    public function reportEndProject(): HasMany
    {
        return $this->hasMany(ReportEndProject::class, "proposal_id");
    }

    public function extensionProject(): HasMany
    {
        return $this->hasMany(ExtensionProject::class, "proposal_id");
    }

    public function formatStatus()
    {
        return self::ARR_STATUS[$this->approval_status] ?? " - ";
    }

    public function scopeRmcProposal($query, $userId)
    {
        return $query->where("is_by_rmc", 1)
            ->where("created_by", $userId)
            ->where("created_at", ">", now()->subDays(2));
    }
}
