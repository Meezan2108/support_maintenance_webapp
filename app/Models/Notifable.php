<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifable extends Model
{
    use HasFactory, SoftDeletes;

    const TARGET_TYPE_USER = "user";
    const TARGET_TYPE_GROUP = "group";

    protected $table = "notifable";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "data" => "array",
        "options" => "array"
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo('notifable');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, "ref_division_id");
    }

    public function log(): HasMany
    {
        return $this->hasMany(NotifableLog::class, "notifable_id");
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, "template_id");
    }
}
