<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RefOtherDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "ref_other_documents";

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];
}
