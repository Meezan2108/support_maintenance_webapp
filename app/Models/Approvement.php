<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approvement extends Model
{
    use HasFactory, SoftDeletes, HasLog;

    protected $table = "approvement";

    const ARR_STATUS = [
        0 => 'Draft',
        1 => 'Submitted',
        2 => 'Amend',
        3 => 'Resubmit',
        4 => 'Approved',
        5 => 'Rejected'
    ];

    const STATUS_TEMP = -1;
    const STATUS_DRAFT = 0;
    const STATUS_SUBMITED = 1;
    const STATUS_AMEND = 2;
    const STATUS_RE_SUBMIT = 3;
    const STATUS_APPROVED = 4;
    const STATUS_REJECTED = 5;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        'comments' => 'array',
        'options' => 'array'
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo("approvable");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function formatStatus($status)
    {
        return self::ARR_STATUS[$status] ?? " - ";
    }
}
