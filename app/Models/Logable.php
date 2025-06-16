<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Logable extends Model
{
    protected $table = "logable";

    protected $guarded = ["id", "created_at", "updated_at"];

    protected $casts = [
        "before_change" => "array",
        "after_change" => "array",
        "changes" => "array"
    ];

    const ACTION_CREATE = "CREATE";
    const ACTION_UPDATE = "UPDATE";
    const ACTION_DELETE = "DELETE";

    public function reference(): MorphTo
    {
        return $this->morphTo('logable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
