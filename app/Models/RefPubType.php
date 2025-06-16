<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPubType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_pub_type";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, "ref_pub_type_id");
    }
}
