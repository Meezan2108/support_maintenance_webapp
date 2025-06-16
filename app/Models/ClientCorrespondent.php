<?php

// app/Models/ClientCorrespondent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCorrespondent extends Model
{
    protected $fillable = ['client_id', 'name', 'email', 'phone', 'position', 'department'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
