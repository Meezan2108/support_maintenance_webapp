<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ResearcherInvolveable extends Model
{
    use HasFactory, HasLog;

    const TYPE_LEADER = 1;
    const TYPE_RESEARCHER = 2;
    const TYPE_STAFF = 3;

    protected $table = "researcher_involveable";

    protected $guarded = ["id"];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "id");
    }

    public function kpiAchievement(): BelongsTo
    {
        return $this->belongsTo(KpiAchievement::class, "id");
    }

    public function reference(): MorphTo
    {
        return $this->morphTo("researcher_involveable");
    }
}
