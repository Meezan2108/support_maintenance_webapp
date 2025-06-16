<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\PslkmSub\GetPslkmSubDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\PslkmSubRequest;
use App\Http\Requests\ReferenceTable\PslkmSubSearchRequest;
use App\Http\Resources\RefTable\PslkmSubResource;
use App\Models\RefPslkm;
use App\Models\RefPslkmSub;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PslkmSubController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetPslkmSubDatatables $datatables, PslkmSubSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $list = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/PslkmSub/Index', [
            "title" => "Sub PSLKM | Reference Table Management",
            "additional" => [
                "list" => PslkmSubResource::collection($list),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.pslkm-sub.create"),
                "urlIndex" => route("panel.ref-table.pslkm-sub.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $arrStatus = collect(RefPslkm::ARR_STATUS)->map(function ($value, $key) {
            return [
                "id" => $key,
                "description" => $value
            ];
        });

        return Inertia::render('Master/RefTable/PslkmSub/Create', [
            "title" => "Create Sub PSLKM | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "arrStatus" => $arrStatus,
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.pslkm-sub.store"),
                "urlIndex" => route("panel.ref-table.pslkm-sub.index"),
                "urlResourcePslkm" => route("resources.pslkm.index")
            ]
        ]);
    }

    public function store(PslkmSubRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $arrData = $request->validated();
            return RefPslkmSub::create($arrData);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm-sub.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Sub PSLKM Success!"
            ]);
    }


    public function show(Request $request, RefPslkmSub $sub)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/PslkmSub/Show', [
            "title" => "Show Sub PSLKM | Reference Table Management",
            "additional" => [
                "data" => $sub->load("pslkm")->toArray(),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.pslkm-sub.edit", $sub),
                "urlIndex" => route("panel.ref-table.pslkm-sub.index")
            ]

        ]);
    }

    public function edit(Request $request, RefPslkmSub $sub)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $arrStatus = collect(RefPslkmSub::ARR_STATUS)->map(function ($value, $key) {
            return [
                "id" => $key,
                "description" => $value
            ];
        });

        return Inertia::render('Master/RefTable/PslkmSub/Edit', [
            "title" => "Edit Sub PSLKM | Reference Table Management",
            "additional" => [
                "data" => $sub,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
                "arrStatus" => $arrStatus,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.pslkm-sub.update", $sub),
                "urlShow" => route("panel.ref-table.pslkm-sub.show", $sub),
                "urlIndex" => route("panel.ref-table.pslkm-sub.index"),
                "urlResourcePslkm" => route("resources.pslkm.index")
            ]
        ]);
    }

    public function update(PslkmSubRequest $request, RefPslkmSub $sub)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $sub) {
            $arrData = $request->validated();
            $sub->update($arrData);

            return $sub;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm-sub.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Sub PSLKM Success!"
            ]);
    }

    public function destroy(Request $request, RefPslkmSub $sub)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($sub) {
            $sub->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm-sub.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Sub PSLKM Success!"
            ]);
    }
}
