<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IssueTypeController extends Controller
{
     public function index()
    {
        $issueTypes = IssueType::all();
        return Inertia::render('IssueTypes/Index', [
            'issueTypes' => $issueTypes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        IssueType::create($request->only('name'));

        return redirect()->back()->with('success', 'Issue Type created.');
    }

    public function update(Request $request, IssueType $issueType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $issueType->update($request->only('name'));

        return redirect()->back()->with('success', 'Issue Type updated.');
    }

    public function destroy(IssueType $issueType)
    {
        $issueType->delete();

        return redirect()->back()->with('success', 'Issue Type deleted.');
    }
}
