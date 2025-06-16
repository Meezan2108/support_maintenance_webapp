<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiAchievement extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "kpi_achievement";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        "date" => "date:Y-m-d"
    ];

    const ARR_CATEGORY = [
        1 => "Publication",
        2 => "Output R&D",
        3 => "Intellectual Property Right",
        4 => "Commercialization",
        5 => "Analytical Service Lab",
        6 => "Imported Germplasm",
        7 => "Recognition",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiCategory::class, "category_id");
    }

    public function reff(): MorphTo
    {
        return $this->morphTo("reff");
    }

    public function researcher(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "researcher_involveable", "kpi_achievement_id", "user_id")
            ->withPivot("type", "name");
    }
}
