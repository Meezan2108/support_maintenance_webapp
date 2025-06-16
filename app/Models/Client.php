<?php

// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'location_id',];

    public function correspondents()
    {
        return $this->hasMany(ClientCorrespondent::class);
    }
    // app/Models/Client.php

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
