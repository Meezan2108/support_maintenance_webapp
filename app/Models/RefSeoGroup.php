<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSeoGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_seo_group";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefSeoCategory::class, 'ref_seo_category_id');
    }

    public function area(): HasMany
    {
        return $this->hasMany(RefSeoArea::class, "ref_seo_group_id");
    }
}
