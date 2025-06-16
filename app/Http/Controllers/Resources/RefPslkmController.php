<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\RefPslkm;
use Illuminate\Http\Request;

class RefPslkmController extends Controller
{
    public function index(Request $request)
    {
        $pslkm = RefPslkm::query()
            ->where('description', 'LIKE', "%{$request->search}%")
            ->when($request->status ?? false, function ($query) use ($request) {
                return $query->where("status", $request->status);
            })
            ->select('id', 'description')
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search PSLKM',
            'data' => $pslkm
        ], 200);
    }

    public function show(Request $request, RefPslkm $pslkm)
    {
        return response([
            'message' => 'Show PSLKM',
            'data' => $pslkm
        ], 200);
    }
}
