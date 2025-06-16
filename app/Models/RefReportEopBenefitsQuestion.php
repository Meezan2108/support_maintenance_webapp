<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefReportEopBenefitsQuestion extends Model
{
    use HasFactory;

    protected $table = "ref_report_eop_benefits_question";

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'rules' => 'array'
    ];

    public function answer(): HasMany
    {
        return $this->hasMany(ReportEopBenefits::class);
    }
}
