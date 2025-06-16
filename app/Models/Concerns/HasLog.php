<?php

namespace App\Models\Concerns;

use App\Models\Logable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLog
{
    /**
     * Model belongs to created by
     *
     * @return MorphMany
     */
    public function logable(): MorphMany
    {
        return $this->morphMany(Logable::class, 'logable');
    }

    public function getBeforeChange()
    {
        return $this->getAttributes();
    }
}
