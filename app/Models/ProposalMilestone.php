<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProposalMilestone extends Model
{
    use HasFactory, SoftDeletes, HasLog;

    protected $table = "proposal_milestone";

    protected $guarded = ["id"];

    const TYPE_PROPOSAL = "proposal";
    const TYPE_EXTENSION = "extension";

    protected $casts = [
        "from" => "date:Y-m-d",
        "completion_date" => "date:Y-m"
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function scopeFilterByQuarter($query, $year, $quarter)
    {
        $startMonth = ($quarter - 1) * 3 + 1;
        $endMonth = $startMonth + 2;

        $startYearMonth = $year . "-" . str_pad($startMonth, 2, 0, STR_PAD_LEFT);
        $endYearMonth = $year . "-" . str_pad($endMonth, 2, 0, STR_PAD_LEFT);

        return $query->whereBetween(
            DB::raw("CONVERT(VARCHAR(7), [from], 120)"),
            [$startYearMonth, $endYearMonth]
        );
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }
}
