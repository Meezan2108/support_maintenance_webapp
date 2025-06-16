<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reminder";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        "options" => "array"
    ];

    const REPEAT_YEARLY = 1;
    const REPEAT_BIANUALLY = 2;
    const REPEAT_TRIANUALLY = 3;
    const REPEAT_QUARTERLY = 4;
    const REPEAT_MONTHLY = 5;
    const REPEAT_WEEKLY = 6;
    const REPEAT_DAYLY = 7;

    const ARR_REPEAT_TYPE = [
        1 => [
            "id" => 1,
            "description" => "Every Year",
            "options" => ["hour:24", "day:31", "month:12"]
        ],
        2 => [
            "id" => 2,
            "description" => "Every 6 Months",
            "options" => ["hour:24", "day:31", "month:6"]
        ],
        3 => [
            "id" => 3,
            "description" => "Every 4 Months",
            "options" => ["hour:24", "day:31", "month:4"]
        ],
        4 => [
            "id" => 4,
            "description" => "Quarterly",
            "options" => ["hour:24", "day:31", "month:3"]
        ],
        5 => [
            "id" => 5,
            "description" => "Every Month",
            "options" => ["hour:24", "day:31"]
        ],
        6 => [
            "id" => 6,
            "description" => "Every Week",
            "options" => ["hour:24", "day:7"]
        ],
        7 => [
            "id" => 7,
            "description" => "Every Day",
            "options" => ["hour:24"]
        ]
    ];

    const KEY_OPTIONS = ["hour", "day", "month"];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RefReminderCategory::class, 'ref_reminder_category_id');
    }
}
