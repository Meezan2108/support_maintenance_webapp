<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefDivision;
use App\Models\RefForCategory;
use Illuminate\Http\Request;

class RefForCategoryController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefForCategory::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->selectRaw("id, code + ' - ' + description as description")
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search FOR Category',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefForCategory $for_category)
    {
        return response([
            'message' => 'Show FOR Category',
            'data' => $for_category
        ], 200);
    }
}
