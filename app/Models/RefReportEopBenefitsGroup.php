<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefReportEopBenefitsGroup extends Model
{
    use HasFactory;

    protected $table = "ref_report_eop_benefits_group";

    protected $guarded = [];

    public function section(): HasMany
    {
        return $this->hasMany(RefReportEopBenefitsSection::class, 'ref_report_eop_benefits_group_id');
    }
}
