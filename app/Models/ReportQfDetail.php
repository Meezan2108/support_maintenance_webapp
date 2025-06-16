<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportQfDetail extends Model
{
    use HasFactory, HasLog;

    protected $table = "report_qf_detail";

    protected $guarded = [];

    public $timestamps = false;

    public function report(): BelongsTo
    {
        return $this->belongsTo(ReportQuarterlyFinancial::class);
    }
}
