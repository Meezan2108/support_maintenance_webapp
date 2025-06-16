<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefForGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_for_group";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function area(): HasMany
    {
        return $this->hasMany(RefForArea::class, "ref_for_group_id");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefForCategory::class, "ref_for_category_id");
    }
}
