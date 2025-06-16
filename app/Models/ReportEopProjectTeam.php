<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportEopProjectTeam extends Model
{
    use HasFactory;

    protected $table = "report_eop_project_team";

    protected $guarded = [];

    public function report(): BelongsTo
    {
        return $this->belongsTo(ReportEndProject::class);
    }
}
