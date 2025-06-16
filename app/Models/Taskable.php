<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taskable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "taskable";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    const TARGET_TYPE_USER = "user";
    const TARGET_TYPE_GROUP = "group";

    protected $casts = [
        "options" => "array"
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo('taskable');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, "ref_division_id");
    }

    public function submitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, "submited_user_id");
    }
}
