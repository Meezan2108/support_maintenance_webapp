<?php

namespace App\Http\Controllers;

use App\Models\StMember;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StMemberController extends Controller
{
    public function index()
    {
        return Inertia::render('StMembers/Index', [
            'members' => StMember::orderBy('id', 'desc')->get(),
            'urlStore' => route('st-members.store'),
                    'flash' => [
            'success' => session('success'),
            'error' => session('error'),
        ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'hired_date' => 'required|date',
            'status' => 'required|string|max:100',
        ]);

        StMember::create($request->only('full_name', 'hired_date', 'status'));

        return redirect()->route('st-members.index')->with('success', '✅ Member added successfully!');
    }

    public function update(Request $request, $id)
    {
        $member = StMember::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'hired_date' => 'required|date',
            'status' => 'required|string|max:100',
        ]);

        $member->update($request->only('full_name', 'hired_date', 'status'));

          return redirect()->route('st-members.index')->with('success', '✅ Member updated successfully!');
    }

    public function destroy($id)
    {
        StMember::destroy($id);
        return redirect()->route('st-members.index')->with('success', '🗑️ Member deleted successfully!');
    }
}
