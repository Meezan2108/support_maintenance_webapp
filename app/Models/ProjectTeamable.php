<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProjectTeamable extends Model
{
    use HasFactory, HasLog;

    const TYPE_LEADER = 1;
    const TYPE_RESEARCHER = 2;
    const TYPE_STAFF = 3;

    protected $table = "project_teamable";

    protected $guarded = ["id"];

    public $timestamps = false;

    public function reference(): MorphTo
    {
        return $this->morphTo('project_teamable');
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
