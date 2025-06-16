<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Http\Requests\ReferenceTable\LocationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();

        return Inertia::render('Master/RefTable/Location/Index', [
            'title' => 'Location Master Data',
            'locations' => $locations,
        ]);
    }

    public function create()
    {
        return Inertia::render('Master/RefTable/Location/Create', [
            'title' => 'Create New Location',
        ]);
    }

  public function store(LocationRequest $request)
    {
        $data = $request->validated();

        $location = Location::create([
            'location_name' => $data['location_name'],
            'code' => $data['code'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Location saved successfully.');
    }

    public function edit(Location $location)
    {
        return Inertia::render('Master/RefTable/Location/Edit', [
            'title' => 'Edit Location',
            'location' => $location,
        ]);
    }

    public function update(LocationRequest $request, Location $location)
    {
        DB::transaction(function () use ($request, $location) {
            $location->update($request->validated());
        });

        return redirect()->route('panel.ref-table.location.index')->with('message', [
            'status' => 'success',
            'message' => 'Location updated successfully.',
        ]);
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('panel.ref-table.location.index')->with('message', [
            'status' => 'success',
            'message' => 'Location deleted successfully.',
        ]);
    }
}
