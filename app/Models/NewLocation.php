<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewLocation extends Model
{
protected $table = 'new_locations';

protected $fillable = ['location_name', 'code'];

    use HasFactory;
}
