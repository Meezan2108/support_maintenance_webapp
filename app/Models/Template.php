<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PROPOSAL_SUBMIT = "proposal-submit";
    const TYPE_PROPOSAL_NEED_TO_REVIEW = "proposal-need-to-review";
    const TYPE_PROPOSAL_REVIEWED = "proposal-reviewed";
    const TYPE_PROPOSAL_REVIEWED_SUBMIT = "proposal-reviewed-submit";
    const TYPE_PROPOSAL_STATUS_UPDATED = "proposal-status-updated";

    const TYPE_REPORT_SUBMIT = "report-submit";
    const TYPE_REPORT_NEED_TO_REVIEW = "report-need-to-review";
    const TYPE_REPORT_REVIEWED = "report-reviewed";
    const TYPE_REPORT_REVIEWED_SUBMIT = "report-reviewed-submit";
    const TYPE_REPORT_STATUS_UPDATED = "report-status-updated";

    const TYPE_KPI_SUBMIT = "kpi-submit";
    const TYPE_KPI_NEED_TO_REVIEW = "kpi-need-to-review";
    const TYPE_KPI_REVIEWED = "kpi-reviewed";
    const TYPE_KPI_REVIEWED_SUBMIT = "kpi-reviewed-submit";
    const TYPE_KPI_STATUS_UPDATED = "kpi-status-updated";

    const TYPE_NEW_DOCUMENTATION = "new-documentation";

    const TYPE_PROPOSAL_DIVISION_INFO = "proposal-division-info";
    const TYPE_REPORT_DIVISION_INFO = "report-division-info";
    const TYPE_KPI_DIVISION_INFO = "kpi-division-info";


    protected $table = "template";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "options" => "array"
    ];
}
