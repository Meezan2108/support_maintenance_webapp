<?php

namespace App\Models;

use App\Models\Concerns\HasLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles, HasLog;

    const FILEABLE_PROFILE_CODE = "profile_picture";

    const ROLE_SUPERADMIN = 1;
    const ROLE_LKM_DIRECTOR = 2;
    const ROLE_RND_COORDINATOR = 3;
    const ROLE_DIVISION_DIRECTOR = 4;
    const ROLE_RMC = 5;
    const ROLE_RESEARCHER = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nric', 'email', 'code', 'staf_id',
        'ref_division_id', 'ref_position_id',
        'salutation', 'qualification', 'status', 'picture',
        'tel_no', 'fax_no', 'working_address',
        'password', 'researcher_id',
        'created_by', 'updated_by', 'deleted_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'original_data' => 'array'
    ];

    public function activeRole()
    {
        return $this->roles->first();
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(RefDivision::class, "ref_division_id");
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(RefPosition::class, "ref_position_id");
    }

    public function fileable(): MorphOne
    {
        return $this->morphOne(Fileable::class, 'fileable');
    }

    public function targetKpi(): HasMany
    {
        return $this->hasMany(TargetKpi::class, "user_id");
    }

    public function originalable(): MorphMany
    {
        return $this->morphMany(Originalable::class, "originalable");
    }

    public function kpiAchievement(): BelongsToMany
    {
        return $this->belongsToMany(KpiAchievement::class, "researcher_involveable", "kpi_achievement_id", "user_id");
    }
}
