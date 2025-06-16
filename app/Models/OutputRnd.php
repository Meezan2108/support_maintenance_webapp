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

class OutputRnd extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "output_rnd";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const CATEGORY_ID = 13;
    const CATEGORY_CODE = "output-rnd";
    const FILEABLE_FILE_CODE = "outputrnd_file";

    protected $casts = [
        "date_output" => "date:Y-m-d"
    ];

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function output_type(): BelongsTo
    {
        return $this->belongsTo(RefOutputType::class, "type");
    }

    public function output_status(): BelongsTo
    {
        return $this->belongsTo(RefOutputStatus::class, "status");
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

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
