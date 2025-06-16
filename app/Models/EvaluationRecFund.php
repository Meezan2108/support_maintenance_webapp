<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationRecFund extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "evaluation_rec_fund";

    protected $guarded = [];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function costSeries(): BelongsTo
    {
        return $this->belongsTo(RefProjectCostSeries::class);
    }
}
