<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefOutputType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_output_types";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function output(): HasMany
    {
        return $this->hasMany(OutputRnd::class, "type");
    }

    public function targetKpiCategory(): BelongsTo
    {
        return $this->belongsTo(RefTargetKpiCategory::class, "ref_target_kpi_category_id");
    }
}
