<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefDivision;
use App\Models\RefForCategory;
use App\Models\RefForGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefForGroupController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'ref_for_category_id' => ['nullable', Rule::exists('ref_for_category', 'id')]
        ]);

        $positions = RefForGroup::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where('ref_for_category_id', $request->ref_for_category_id)
            ->selectRaw("id, code + ' - ' + description as description")
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search FOR Group',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefForGroup $for_group)
    {
        return response([
            'message' => 'Show FOR Group',
            'data' => $for_group
        ], 200);
    }
}
