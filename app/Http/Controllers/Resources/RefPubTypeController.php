<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefPubType;
use Illuminate\Http\Request;

class RefPubTypeController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefPubType::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Publication Type',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefPubType $pub_type)
    {
        return response([
            'message' => 'Show Publication Type',
            'data' => $pub_type
        ], 200);
    }
}
