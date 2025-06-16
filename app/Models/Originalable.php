<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Originalable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "originalable";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "original_data" => "array"
    ];

    public function referenceTable(): MorphTo
    {
        return $this->morphTo('originalable');
    }
}
