<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location_Branch extends Model
{
    use HasFactory;

    //This line allows mass assignment for the 'name' field
    protected $fillable = ['location_name'];
}
