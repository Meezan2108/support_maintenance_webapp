<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefReportEopBenefitsSection extends Model
{
    use HasFactory;

    protected $table = "ref_report_eop_benefits_section";

    protected $guarded = [];

    public function question(): HasMany
    {
        return $this->hasMany(RefReportEopBenefitsQuestion::class, 'ref_report_eop_benefits_section_id');
    }
}
