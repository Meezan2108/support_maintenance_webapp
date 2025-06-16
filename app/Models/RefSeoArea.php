<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefSeoArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_seo_area";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(RefSeoGroup::class, 'ref_seo_group_id');
    }
}
