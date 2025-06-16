<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderSendLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reminder_send_log";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = -1;

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class, 'reminder_id');
    }

    public function ref(): MorphTo
    {
        return $this->morphTo('ref');
    }
}
