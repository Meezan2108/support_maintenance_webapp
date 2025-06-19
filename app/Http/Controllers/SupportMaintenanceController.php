<?php

namespace App\Http\Controllers;

use App\Models\SupportMaintenance;
use App\Models\Project;
use App\Models\IssueType;
use App\Models\StMember;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;


class SupportMaintenanceController extends Controller
{
    public function index()
{
    return Inertia::render('SupportMaintenance/Index', [
        'records' => SupportMaintenance::with(['project.client', 'issueType'])->get()
    ]);
}

public function create()
{
    $projects = Project::with('client.correspondents')->get();
    return Inertia::render('SupportMaintenance/Create', [
        //'projects' => Project::with('client')->get(),
        'projects' => $projects,
        // 'issue_types' => IssueType::all(),
        // 'st_members' => StMember::all(),
        'issue_types' => IssueType::all(),
        'st_members' => StMember::all(),
        'ticket_id' => $this->generateTicketID(),
        
    ]);
}

// public function store(Request $request)
// {
//     $validated = $request->validate([
//         'project_id' => 'required|exists:projects,id',
//         'client_id' => 'required|exists:clients,id',
//         'request_date' => 'required|date',
//         'reported_by' => 'required|string',
//         'department_unit' => 'nullable|string',
//         'issue_type_id' => 'required|exists:issue_types,id',
//         'description' => 'nullable|string',
//         'reported_to' => 'required|exists:st_members,id',
//         'priority' => 'required|in:Low,Medium,High',
//         'status' => 'required|in:Pending,Done,Cancelled',
//         'starting_date' => 'nullable|date',
//         'completion_date' => 'nullable|date|after_or_equal:starting_date',
//         'solution_summary' => 'nullable|string',
//         'follow_up_required' => 'required|in:Yes,No',
//         'remarks' => 'nullable|string',
//     ]);

//     // ✅ Check for duplicate
//     $exists = SupportMaintenance::where('project_id', $validated['project_id'])
//         ->where('client_id', $validated['client_id'])
//         ->where('issue_type_id', $validated['issue_type_id'])
//         ->exists();

//     if ($exists) {
//         return redirect()->back()->with('message', 'This support ticket already exists!');
//     }

//     // ✅ Proceed if not duplicate
//     $validated['ticket_id'] = $this->generateTicketID();

//     if ($validated['starting_date'] && $validated['completion_date']) {
//         $validated['duration_days'] = now()->parse($validated['completion_date'])->diffInDays($validated['starting_date']);
//     }

//     SupportMaintenance::create($validated);

// return redirect()->route('support-maintenance.index')->with('success', 'Support ticket created successfully.');

// }
public function store(Request $request)
{
    $validated = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'client_id' => 'required|exists:clients,id',
        'request_date' => 'required|date',
        //'reported_by' => 'required|string',
        'reported_by' => 'required|string',
        'department_unit' => 'nullable|string',
        'issue_type_id' => 'required|exists:issue_types,id',
        'description' => 'nullable|string',
        'reported_to' => 'required|exists:st_members,id',
        //'reported_to' => 'required|string',
        'priority' => 'required|in:Low,Medium,High',
        'status' => 'required|in:Pending,Done,Cancelled',
        'starting_date' => 'nullable|date',
        'completion_date' => 'nullable|date|after_or_equal:starting_date',
        'solution_summary' => 'nullable|string',
        'follow_up_required' => 'required|in:Yes,No',
        'remarks' => 'nullable|string',
    ]);

    $validated['ticket_id'] = $this->generateTicketID();

    if ($validated['starting_date'] && $validated['completion_date']) {
        $validated['duration_days'] = now()->parse($validated['completion_date'])->diffInDays($validated['starting_date']);
    }

    SupportMaintenance::create($validated);

    return redirect()->route('support-maintenance.index')->with('success', 'Support & Maintenance ticket created.');
}

public function destroy($id)
{
    $record = SupportMaintenance::findOrFail($id);
    $record->delete();

    // For Inertia, returning JSON is a good practice to handle in frontend
    return redirect()->route('support-maintenance.index')->with('success', 'Ticket delete successfully.');
}


// public function update(Request $request, $id)
// {
//     $ticket = SupportMaintenance::findOrFail($id);

//     $validated = $request->validate([
//         'project_id' => 'required|exists:projects,id',
//         'client_id' => 'required|exists:clients,id',
//         'request_date' => 'required|date',
//         //'reported_by' => 'required|string',
//         'reported_by' => 'required|string',
//         'department_unit' => 'nullable|string',
//         'issue_type_id' => 'required|exists:issue_types,id',
//         'description' => 'nullable|string',
//         'reported_to' => 'required|exists:st_members,id',
//         'priority' => 'required|string',
//         'status' => 'required|string',
//         'starting_date' => 'nullable|date',
//         'completion_date' => 'nullable|date',
//         'duration_days' => 'nullable|integer',
//         'solution_summary' => 'nullable|string',
//         'follow_up_required' => 'required|in:Yes,No',
//         'remarks' => 'nullable|string',
//     ]);

//     $ticket->update($validated);

//     //return redirect()->route('support-maintenance.index')->with('success', 'Ticket updated successfully.');
//     // Redirect to show page with success flash message
//     return redirect()->route('support-maintenance.show', $ticket->id)
//                      ->with('success', 'Ticket information has been updated.');

// }

public function update(Request $request, $id)
{
    $ticket = SupportMaintenance::findOrFail($id);

    if ($request->has('status') && $request->keys() === ['status']) {
        $request->validate(['status' => 'required|in:Pending,Done,Cancelled']);
        $ticket->update(['status' => $request->status]);

        return redirect()->route('support-maintenance.index')->with('success', 'Ticket marked as Done.');
    }

    // Otherwise, do full validation
    $validated = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'client_id' => 'required|exists:clients,id',
        'request_date' => 'required|date',
        'reported_by' => 'required|string',
        'department_unit' => 'nullable|string',
        'issue_type_id' => 'required|exists:issue_types,id',
        'description' => 'nullable|string',
        'reported_to' => 'required|exists:st_members,id',
        'priority' => 'required|string',
        'status' => 'required|string',
        'starting_date' => 'nullable|date',
        'completion_date' => 'nullable|date',
        'duration_days' => 'nullable|integer',
        'solution_summary' => 'nullable|string',
        'follow_up_required' => 'required|in:Yes,No',
        'remarks' => 'nullable|string',
    ]);

    $ticket->update($validated);

    return redirect()->route('support-maintenance.show', $ticket->id)
                     ->with('success', 'Ticket information has been updated.');
}




public function edit($id)
{
    $ticket = SupportMaintenance::with('project.client.correspondents')->findOrFail($id);

    return Inertia::render('SupportMaintenance/Edit', [
        'ticket' => $ticket,
        'projects' => Project::with('client.correspondents')->get(),
        'issue_types' => IssueType::all(),
        'st_members' => STMember::all(),
        'reported_by_obj' => \App\Models\ClientCorrespondent::where('name', $ticket->reported_by)->first(),
    ]);
}

// public function show($id)
// {
//     $record = SupportMaintenance::with(['project.client', 'issueType', 'reported_by', 'reported_to', 'project.client.correspondents'])
//         ->findOrFail($id);

//     return Inertia::render('SupportMaintenance/Show', [
//         'record' => $record,
//     ]);
// }

public function show($id)
{
    $record = SupportMaintenance::with([
        'project.client',
        'client',
        'project.client.correspondents',
        'issueType',
        //'reportedBy',
        'reportedTo',
    ])->findOrFail($id);

    return Inertia::render('SupportMaintenance/Show', [
        'ticket' => $record,
    ]);
}

public function bulkDestroy(Request $request): RedirectResponse
{
    $ids = $request->input('ids', []);

    if (!is_array($ids) || empty($ids)) {
        return redirect()->back()->with('error', 'No IDs provided');
    }

    $ids = array_map('intval', $ids);

    SupportMaintenance::whereIn('id', $ids)->delete();

    return redirect()->route('support-maintenance.index')->with('success', 'Selected tickets deleted successfully');
}


private function generateTicketID()
{
    $prefix = 'SM' . now()->format('ym'); // e.g., SM2506
    $latest = SupportMaintenance::where('ticket_id', 'like', "$prefix%")->latest()->first();
    $nextNumber = $latest ? ((int)substr($latest->ticket_id, -3)) + 1 : 1;
    return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
}

}
