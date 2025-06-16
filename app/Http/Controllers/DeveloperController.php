<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeveloperController extends Controller
{
        public function index()
    {
        $developers = Developer::all();
        return Inertia::render('Developer/Index', ['developers' => $developers]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Developer::create($request->only('name'));
        return redirect()->back();
    }

    public function update(Request $request, Developer $developer)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $developer->update($request->only('name'));
        return redirect()->back();
    }

    public function destroy(Developer $developer)
    {
        $developer->delete();
        return redirect()->back();
    }
}
