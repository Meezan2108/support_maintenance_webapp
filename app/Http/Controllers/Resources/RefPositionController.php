<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefPosition;
use Illuminate\Http\Request;

class RefPositionController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefPosition::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Positions',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefPosition $position)
    {
        return response([
            'message' => 'Show Position',
            'data' => $position
        ], 200);
    }
}
