<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefSeoArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefSeoAreaController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'ref_seo_group_id' => ['nullable', Rule::exists('ref_seo_group', 'id')]
        ]);

        $positions = RefSeoArea::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where('ref_seo_group_id', $request->ref_seo_group_id)
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search SEO Area',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefSeoArea $seo_area)
    {
        return response([
            'message' => 'Show SEO Area',
            'data' => $seo_area
        ], 200);
    }
}
