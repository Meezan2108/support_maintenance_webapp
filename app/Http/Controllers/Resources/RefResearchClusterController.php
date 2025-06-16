<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefPosition;
use App\Models\RefResearchCluster;
use Illuminate\Http\Request;

class RefResearchClusterController extends Controller
{
    public function index(Request $request)
    {
        $data = RefResearchCluster::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Research Cluster',
            'data' => $data
        ], 200);
    }

    public function show(Request $request, RefResearchCluster $research_cluster)
    {
        return response([
            'message' => 'Show Research Cluster',
            'data' => $research_cluster
        ], 200);
    }
}
