<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefDivision;
use App\Models\RefForArea;
use App\Models\RefForCategory;
use App\Models\RefForGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RefForAreaController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'ref_for_group_id' => ['nullable', Rule::exists('ref_for_group', 'id')]
        ]);

        $positions = RefForArea::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where('ref_for_group_id', $request->ref_for_group_id)
            ->selectRaw("id, code + ' - ' + description as description")
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search For Area',
            'data' => $positions
        ], 200);
    }

    public function show(Request $request, RefForArea $for_area)
    {
        return response([
            'message' => 'Show For Area',
            'data' => $for_area
        ], 200);
    }
}
