<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefSeoCategory;
use Illuminate\Http\Request;

class RefSeoCategoryController extends Controller
{
    public function index(Request $request)
    {
        $positions = RefSeoCategory::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search SEO Category',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefSeoCategory $seo_category)
    {
        return response([
            'message' => 'Show SEO Category',
            'data' => $seo_category
        ], 200);
    }
}
