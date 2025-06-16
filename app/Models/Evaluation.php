<?php

namespace App\Models;

use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge;

    protected $table = "evaluation";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function recomendedFund(): HasMany
    {
        return $this->hasMany(EvaluationRecFund::class);
    }

    public function answer(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class);
    }

    public function approvement(): MorphMany
    {
        return $this->morphMany(Approvement::class, 'approvable');
    }
}
