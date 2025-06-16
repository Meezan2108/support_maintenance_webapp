<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Granttchartable extends Model
{
    use HasFactory, HasLog;

    protected $table = "granttchartable";

    protected $guarded = [];

    public $casts = [
        'options' => 'array',
        'from' => 'date'
    ];

    public $timestamps = false;

    public function reference(): MorphTo
    {
        return $this->morphTo("grantchartable");
    }
}
