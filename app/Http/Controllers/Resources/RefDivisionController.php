<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefDivision;
use Illuminate\Http\Request;

class RefDivisionController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefDivision::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->where("is_active", 1)
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Divisions',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefDivision $division)
    {
        return response([
            'message' => 'Show Devision',
            'data' => $division
        ], 200);
    }
}
