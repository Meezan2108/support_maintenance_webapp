<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPslkmSub extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = -1;

    const ARR_STATUS = [
        1 => "Active",
        -1 => "Disabled"
    ];

    protected $table = "ref_pslkm_sub";

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = ["status_text"];

    public function pslkm(): BelongsTo
    {
        return $this->belongsTo(RefPslkm::class, "ref_pslkm_id");
    }

    public function getStatusTextAttribute()
    {
        return self::ARR_STATUS[$this->status] ?? " - ";
    }
}
