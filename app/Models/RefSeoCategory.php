<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSeoCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_seo_category";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function group(): HasMany
    {
        return $this->hasMany(RefSeoGroup::class, "ref_seo_category_id");
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(RefSeoSector::class, "ref_seo_sector_id");
    }
}
