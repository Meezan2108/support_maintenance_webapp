<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewLocationController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'location_name' => 'required|string|max:64',
        'code' => 'required|string|max:10',
    ]);

    Location::create([
        'location_name' => $request->location_name,
        'code' => $request->code,
    ]);

    return redirect()->route('panel.ref-table.location.index');
}

}


