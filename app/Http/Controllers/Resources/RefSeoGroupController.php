<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefForGroup;
use App\Models\RefSeoGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefSeoGroupController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'ref_seo_category_id' => ['nullable', Rule::exists('ref_seo_category', 'id')]
        ]);

        $positions = RefSeoGroup::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where('ref_seo_category_id', $request->ref_seo_category_id)
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search SEO Group',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefSeoGroup $seo_group)
    {
        return response([
            'message' => 'Show SEO Group',
            'data' => $seo_group
        ], 200);
    }
}
