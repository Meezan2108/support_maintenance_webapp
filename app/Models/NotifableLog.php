<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifableLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "notifable_log";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    public function notifable(): BelongsTo
    {
        return $this->belongsTo(Notifable::class, "notifable_id");
    }
}
