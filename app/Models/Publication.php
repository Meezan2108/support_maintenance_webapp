<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasOriginal;
use App\Models\Concerns\HasPersonInCharge;
use App\Models\Contracts\KpiAchievementDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model implements KpiAchievementDetail
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog, HasOriginal;

    protected $table = "publication";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const CATEGORY_ID = 1;
    const CATEGORY_CODE = 'publication';
    const FILEABLE_FILE_CODE = "publication_file";

    protected $casts = [
        "date_published" => "date:Y-m-d"
    ];

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RefPubType::class, "ref_pub_type_id");
    }

    public function kpiAchievement(): MorphOne
    {
        return $this->morphOne(KpiAchievement::class, "reff");
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

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function researcherInvolved(): MorphMany
    {
        return $this->morphMany(ResearcherInvolveable::class, "researcher_involveable");
    }
}
