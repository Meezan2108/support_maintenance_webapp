<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefProjectCostSeries extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_project_cost_series";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
}
