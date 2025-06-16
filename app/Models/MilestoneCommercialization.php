<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneCommercialization extends Model
{
    use HasFactory, HasLog;

    protected $table = "milestone_commercialization";

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        "date" => "date:Y-m-d"
    ];

    const ARR_CATEGORY = [
        1 => "Product",
        2 => "Technology"
    ];

    protected $appends = ["category_description"];

    public function reportMilestone(): BelongsTo
    {
        return $this->belongsTo(ReportMilestone::class, 'report_milestone_id');
    }

    public function getCategoryDescriptionAttribute()
    {
        return $this->formatCategory($this->category);
    }

    public function formatCategory($category)
    {
        return self::ARR_CATEGORY[$category] ?? " - ";
    }
}
