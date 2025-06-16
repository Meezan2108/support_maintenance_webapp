<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovementStep extends Model
{
    use HasFactory;

    protected $table = "approvement_step";

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        'comments' => 'array',
        'options' => 'array'
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo("approvementstepable");
    }

    public function reviewer1(): BelongsTo
    {
        return $this->belongsTo(User::class, "reviewer_1");
    }

    public function reviewer2(): BelongsTo
    {
        return $this->belongsTo(User::class, "reviewer_2");
    }

    public function reviewer3(): BelongsTo
    {
        return $this->belongsTo(User::class, "reviewer_3");
    }
}
