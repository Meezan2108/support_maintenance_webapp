<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefResearchCluster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_research_cluster";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
}
