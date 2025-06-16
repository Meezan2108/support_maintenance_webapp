<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Location;
// use Inertia\Inertia;


// class LocationController extends Controller
// {
//     public function index()
//     {
//         $locations = Location::all();
//         return Inertia::render('Locations/Index', [
//             'locations' => $locations,
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//         ]);

//         Location::create(['name' => $request->name]);

//         return redirect()->back()->with('success', 'Location added!');
//     }
// }

use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        return Inertia::render('Locations/Index', [
            'locations' => Location::all(),
            'urlStore' => route('locations.store'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Location::create(['name' => $request->name]);

        return redirect()->route('locations.index');
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $location->update(['name' => $request->name]);

        return redirect()->route('locations.index');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index');
    }
}

