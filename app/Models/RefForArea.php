<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefForArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_for_area";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(RefForGroup::class, "ref_for_group_id");
    }
}
