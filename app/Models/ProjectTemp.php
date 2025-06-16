<?php

namespace App\Models;

use App\Models\Concerns\HasPersonInCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTemp extends Model
{
    use HasFactory, SoftDeletes, HasPersonInCharge;

    protected $table = "project_temp";

    protected $casts = [
        'data' => 'array'
    ];

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
}
