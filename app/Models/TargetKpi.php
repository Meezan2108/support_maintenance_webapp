<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TargetKpi extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "target_kpi";

    const TYPE_USER = 1;
    const TYPE_DIVISION = 2;
    const TYPE_GLOBAL = 3;

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, 'division_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiCategory::class, "category_id");
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiCategory::class, "sub_category_id");
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiPeriod::class, "period_id");
    }
}
