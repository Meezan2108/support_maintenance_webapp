<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface KpiAchievementDetail
{
    public function projectLeader(): BelongsTo;

    public function kpiAchievement(): MorphOne;

    public function taskable(): MorphOne;

    public function notifable(): MorphOne;

    public function proposal(): BelongsTo;

    public function researcherInvolved(): MorphMany;
}
