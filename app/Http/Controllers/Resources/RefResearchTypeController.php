<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefResearchType;
use Illuminate\Http\Request;

class RefResearchTypeController extends Controller
{
    public function index(Request $request)
    {
        $data = RefResearchType::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Research Cluster',
            'data' => $data
        ], 200);
    }

    public function show(Request $request, RefResearchType $research_type)
    {
        return response([
            'message' => 'Show Research Type',
            'data' => $research_type
        ], 200);
    }
}
