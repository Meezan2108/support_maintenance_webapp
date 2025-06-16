<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'client_id',
        'description',
        'start_date',
        'end_date',
        'developer_id',
        'status',
        'stabilization_start_date',
        'stabilization_end_date',
        'warranty_start_date',
        'warranty_end_date',
        'support_start_date',
        'support_end_date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

        public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

}
