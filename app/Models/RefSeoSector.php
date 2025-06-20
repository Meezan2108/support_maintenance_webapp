<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSeoSector extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_seo_sector";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function category(): HasMany
    {
        return $this->hasMany(RefSeoCategory::class);
    }
}
