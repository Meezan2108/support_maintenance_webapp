<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use App\Models\Contracts\KpiAchievementDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recognition extends Model implements KpiAchievementDetail
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "recognition";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const FILEABLE_FILE_CODE = "recognition_file";

    const CATEGORY_ID = 23;
    const CATEGORY_CODE = "recognition";

    const ARR_TYPE = [
        1 => 'Local',
        2 => 'International'
    ];

    protected $casts = [
        "date" => "date:Y-m-d"
    ];

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kpiAchievement(): MorphOne
    {
        return $this->morphOne(KpiAchievement::class, "reff");
    }

    public function fileable(): MorphMany
    {
        return $this->morphMany(Fileable::class, "fileable");
    }

    public function taskable(): MorphOne
    {
        return $this->morphOne(Taskable::class, "taskable");
    }

    public function notifable(): MorphOne
    {
        return $this->morphOne(Notifable::class, "notifable");
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function recognitionType()
    {
        return self::ARR_TYPE[$this->type] ?? " - ";
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }

    public function researcherInvolved(): MorphMany
    {
        return $this->morphMany(ResearcherInvolveable::class, "researcher_involveable");
    }
}
