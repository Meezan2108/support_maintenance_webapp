<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportEopBenefit extends Model
{
    use HasFactory;

    protected $table = "report_eop_benefit";

    protected $guarded = ['created_at', 'updated_at'];

    public $timestamps = false;

    public function report(): BelongsTo
    {
        return $this->belongsTo(ReportEndProject::class, 'report_end_project_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(RefReportEopBenefitsQuestion::class, 'ref_report_eop_benefits_question_id');
    }
}
