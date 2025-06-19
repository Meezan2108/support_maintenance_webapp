<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Developer;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client:id,name')->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'client_name' => $project->client->name ?? 'N/A',
                'status' => $project->status,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'created_at' => $project->created_at->format('Y-m-d H:i'),
                'updated_at' => $project->updated_at->format('Y-m-d H:i'),
            ];
        });

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function create()
    {
        $developers = Developer::select('id', 'name')->get();

        return Inertia::render('Projects/Create', [
            'clients' => Client::select('id', 'name')->get(),
            'developers' => $developers,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'project_name' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'developer_id' => 'required|exists:developers,id',
                'stabilization_start_date' => 'nullable|date',
                'stabilization_end_date' => 'nullable|date|after_or_equal:stabilization_start_date',
                'warranty_start_date' => 'nullable|date',
                'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
                'support_start_date' => 'nullable|date',
                'support_end_date' => 'nullable|date|after_or_equal:support_start_date',
            ]);

            $data['status'] = 'Planned';
            Project::create($data);

            return redirect()->route('projects.index');

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function show(Project $project)
    {
        $project->load('client','developer');
        return Inertia::render('Projects/Show', compact('project'));
    }

    public function edit(Project $project)
    {
        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'developers' => Developer::all(),
            'clients' => Client::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'project_name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'developer_id' => 'required|exists:developers,id',
            'status' => 'required|string',
            'stabilization_start_date' => 'nullable|date',
            'stabilization_end_date' => 'nullable|date|after_or_equal:stabilization_start_date',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'support_start_date' => 'nullable|date',
            'support_end_date' => 'nullable|date|after_or_equal:support_start_date',
        ]);

        $project->update($data);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    // New method for updating project status
    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|string|in:Planned,In Progress,Completed',
        ]);

        $allowedTransitions = [
            'Planned' => 'In Progress',
            'In Progress' => 'Completed',
        ];

        $currentStatus = $project->status;
        $newStatus = $request->input('status');

        if (!isset($allowedTransitions[$currentStatus]) || $allowedTransitions[$currentStatus] !== $newStatus) {
            return redirect()->back()->withErrors(['status' => 'Invalid status transition.']);
        }

        $project->status = $newStatus;
        $project->save();

        return redirect()->back()->with('success', 'Project status updated successfully!');
    }
}
