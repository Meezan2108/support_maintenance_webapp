<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefOutputStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_output_statuses";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
}
