<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Location;

class ClientController extends Controller
{
        public function index()
    {

        $clients = Client::with(['correspondents', 'location'])
                ->whereNull('deleted_at') // Only active clients
                ->get();

        return Inertia::render('Client/Index', [
            'clients' => $clients
        ]);
    }

    public function update(Request $request, Client $client)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'location_id' => 'required|exists:locations,id',
        'correspondents' => 'array',
        'correspondents.*.name' => 'required|string',
        'correspondents.*.email' => 'nullable|email',
        'correspondents.*.phone' => 'nullable|string',
        'correspondents.*.position' => 'nullable|string',
        'correspondents.*.department' => 'nullable|string',

    ]);

    $client->update(['name' => $data['name'],
    'location_id' => $data['location_id'],
]);

    // Remove existing and re-add (simpler, avoid complex diff logic)
    $client->correspondents()->delete();
    foreach ($data['correspondents'] as $corr) {
        $client->correspondents()->create($corr);
    }

    return redirect('/clients')->with('success', 'Client updated successfully!');
}


  public function create()
{
    return Inertia::render('Client/Create', [
        'locations' => Location::select('id', 'name')->get(), // Only necessary fields
    ]);
}


    // public function show(Client $client)
    // {
    // return Inertia::render('Client/Show', [
    //     'client' => $client->load('correspondents'),
    //     'locations' => Location::all(),
    // ]);
    // }

    public function show(Client $client)
{
    return Inertia::render('Client/Show', [
        'client' => $client->load(['correspondents', 'location']) // make sure location is eager-loaded
    ]);
}


    public function edit(Client $client)
    {
        $client->load('correspondents');

        return Inertia::render('Client/Edit', [
            'client' => $client,
            'locations' => Location::select('id', 'name')->get(),
        ]);
    }

    public function destroy(Client $client)
    {
    $client->delete();

    // return redirect()->back()->with('message', 'Client deleted successfully.');
     return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'correspondents' => 'array',
            'correspondents.*.name' => 'required|string',
            'correspondents.*.email' => 'nullable|email',
            'correspondents.*.phone' => 'nullable|string',
        ]);

        $client = Client::create(['name' => $validated['name'],
        'location_id' => $validated['location_id'], 
    ]);

        foreach ($validated['correspondents'] as $corr) {
            $client->correspondents()->create($corr);
        }

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
