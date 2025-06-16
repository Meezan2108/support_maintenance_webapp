<?php

namespace App\Models;

use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documentation extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge;

    protected $table = "documentations";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    const FILEABLE_FILE_CODE = "documentation_file";

    protected $casts = [
        "submission_date" => "date:Y-m-d"
    ];

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function refCategory(): BelongsTo
    {
        return $this->belongsTo(RefOtherDocument::class, 'category');
    }

    public function fileable(): MorphMany
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function notifable(): MorphOne
    {
        return $this->morphOne(Notifable::class, "notifable");
    }
}
