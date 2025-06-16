<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPslkm extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = -1;

    const ARR_STATUS = [
        1 => "Active",
        -1 => "Disabled"
    ];

    protected $table = "ref_pslkm";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ["status_text"];

    public function sub(): HasMany
    {
        return $this->hasMany(RefPslkmSub::class, "ref_pslkm_id");
    }

    public function getStatusTextAttribute()
    {
        return self::ARR_STATUS[$this->status] ?? " - ";
    }
}
