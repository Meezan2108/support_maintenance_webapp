<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportQuarterlyFinancial extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge, HasLog;

    protected $table = "report_quarterly_financial";

    protected $guarded = ['created_at', 'updated_by', 'deleted_by'];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function approvement(): MorphMany
    {
        return $this->morphMany(Approvement::class, 'approvable');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(ReportQfDetail::class, 'report_quarterly_financial_id');
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
