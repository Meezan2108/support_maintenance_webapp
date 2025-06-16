<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefForCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_for_category";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function group(): HasMany
    {
        return $this->hasMany(RefForGroup::class, 'ref_for_category_id');
    }
}
