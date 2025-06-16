<?php

namespace App\Models\Concerns;

use App\Models\Logable;
use App\Models\Originalable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasOriginal
{
    /**
     * Model has original data
     *
     * @return MorphMany
     */
    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
