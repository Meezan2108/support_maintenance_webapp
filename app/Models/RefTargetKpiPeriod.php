<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefTargetKpiPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_target_kpi_period";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        "options" => "array"
    ];

    public function targetKpi(): HasMany
    {
        return $this->hasMany(TargetKpi::class, "period");
    }
}
