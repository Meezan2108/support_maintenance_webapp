<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_position";

    protected $guarded = ['created_at', 'updated_at'];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
