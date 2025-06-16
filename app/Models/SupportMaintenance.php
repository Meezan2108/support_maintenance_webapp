<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientCorrespondent;
use App\Models\StMember;

class SupportMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'project_id', 'client_id', 'request_date', 'reported_by',
        'department_unit', 'issue_type_id', 'description', 'reported_to',
        'priority', 'status', 'starting_date', 'completion_date',
        'duration_days', 'solution_summary', 'follow_up_required', 'remarks'
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function client() { return $this->belongsTo(Client::class); }
    public function issueType() { return $this->belongsTo(IssueType::class); }
    public function reportedTo() { return $this->belongsTo(StMember::class, 'reported_to'); }
    public function reportedBy(){ return $this->belongsTo(ClientCorrespondent::class, 'reported_by'); }
}


