<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneExpertiseDevelopment extends Model
{
    use HasFactory, HasLog;

    protected $table = "milestone_expertise_development";

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        "date" => "date:Y-m-d"
    ];

    public function reportMilestone(): BelongsTo
    {
        return $this->belongsTo(ReportMilestone::class, 'report_milestone_id');
    }
}
