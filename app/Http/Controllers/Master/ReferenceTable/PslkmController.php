<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\Pslkm\GetPslkmDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\DivisionRequest;
use App\Http\Requests\ReferenceTable\PslkmRequest;
use App\Http\Requests\ReferenceTable\PslkmSearchRequest;
use App\Http\Resources\RefTable\PslkmResource;
use App\Models\RefDivision;
use App\Models\RefPslkm;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PslkmController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetPslkmDatatables $datatables, PslkmSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $list = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/Pslkm/Index', [
            "title" => "PSLKM | Reference Table Management",
            "additional" => [
                "list" => PslkmResource::collection($list),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.pslkm.create"),
                "urlIndex" => route("panel.ref-table.pslkm.index")
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

        return Inertia::render('Master/RefTable/Pslkm/Create', [
            "title" => "Create PSLKM | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "arrStatus" => $arrStatus,
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.pslkm.store"),
                "urlIndex" => route("panel.ref-table.pslkm.index")
            ]
        ]);
    }

    public function store(PslkmRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $arrData = $request->validated();
            return RefPslkm::create($arrData);
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of PSLKM Success!"
            ]);
    }


    public function show(Request $request, RefPslkm $pslkm)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Pslkm/Show', [
            "title" => "PSLKM Show | Reference Table Management",
            "additional" => [
                "data" => $pslkm->toArray(),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.pslkm.edit", $pslkm),
                "urlIndex" => route("panel.ref-table.pslkm.index")
            ]

        ]);
    }

    public function edit(Request $request, RefPslkm $pslkm)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $arrStatus = collect(RefPslkm::ARR_STATUS)->map(function ($value, $key) {
            return [
                "id" => $key,
                "description" => $value
            ];
        });

        return Inertia::render('Master/RefTable/Pslkm/Edit', [
            "title" => "PSLKM | Reference Table Management",
            "additional" => [
                "data" => $pslkm,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
                "arrStatus" => $arrStatus,
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.pslkm.update", $pslkm),
                "urlShow" => route("panel.ref-table.pslkm.show", $pslkm),
                "urlIndex" => route("panel.ref-table.pslkm.index")
            ]
        ]);
    }

    public function update(PslkmRequest $request, RefPslkm $pslkm)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $pslkm) {
            $arrData = $request->validated();
            $pslkm->update($arrData);

            return $pslkm;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of PSLKM Success!"
            ]);
    }

    public function destroy(Request $request, RefPslkm $pslkm)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($pslkm) {
            $pslkm->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.pslkm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of PSLKM Success!"
            ]);
    }
}
