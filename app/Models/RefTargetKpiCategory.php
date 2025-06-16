<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefTargetKpiCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_target_kpi_category";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiCategory::class, 'parent_id');
    }

    public function subCategory(): HasMany
    {
        return $this->hasMany(RefTargetKpiCategory::class, "parent_id");
    }

    public function targetKpi(): HasMany
    {
        return $this->hasMany(TargetKpi::class);
    }
}
