<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefEvaluationQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_evaluation_question";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'options' => 'array'
    ];
}
