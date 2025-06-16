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

class Commercialization extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "commercialization";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const CATEGORY_ID = 17;
    const CATEGORY_CODE = "commercialization";
    const FILEABLE_FILE_CODE = "commercialization_file";

    protected $casts = [
        "date" => "date:Y-m-d"
    ];

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function output_type(): BelongsTo
    {
        return $this->belongsTo(RefOutputType::class, "category");
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
}
