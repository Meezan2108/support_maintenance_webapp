<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefTypeOfFunding extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_type_of_funding";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'original_data' => 'array'
    ];

    public function proposal(): HasMany
    {
        return $this->hasMany(Proposal::class, "ref_type_of_funding_id");
    }
}
