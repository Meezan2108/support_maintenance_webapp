<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefPslkmSub;
use Illuminate\Http\Request;

class RefPslkmSubController extends Controller
{
    public function index(Request $request)
    {
        $sub = RefPslkmSub::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->where("ref_pslkm_id", $request->ref_pslkm_id ?? false)
            ->when($request->status ?? false, function ($query) use ($request) {
                return $query->where("status", $request->status);
            })
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Sub PSLKM',
            'data' => $sub
        ], 200);
    }

    public function show(Request $request, RefPslkmSub $pslkm_sub)
    {
        return response([
            'message' => 'Show PSLKM',
            'data' => $pslkm_sub
        ], 200);
    }
}
