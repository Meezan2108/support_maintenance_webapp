<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\RefPosition;
use App\Models\RefTypeOfFunding;
use Illuminate\Http\Request;

class RefTypeOfFundingController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefTypeOfFunding::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where('type', Proposal::TYPE_EXTERNAL_FUND)
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Positions',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefTypeOfFunding $type_of_funding)
    {
        return response([
            'message' => 'Show Position',
            'data' => $type_of_funding
        ], 200);
    }
}
