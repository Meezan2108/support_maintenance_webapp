<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Objectiveable extends Model
{
    use HasFactory, HasLog;

    protected $table = "objectiveable";

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const STATUS_ACHIEVED = 1;
    const STATUS_NOT_ACHIEVED = 0;

    public function reference(): MorphTo
    {
        return $this->morphTo('objectiveable');
    }
}
