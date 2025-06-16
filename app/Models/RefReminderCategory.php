<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefReminderCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_reminder_category";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function reminder(): HasMany
    {
        return $this->hasMany(Reminder::class, 'ref_reminder_category_id');
    }
}
