<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\Division\GetDivisionDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\DivisionRequest;
use App\Http\Requests\ReferenceTable\DivisionSearchRequest;
use App\Http\Resources\RefTable\DivisionResource;
use App\Models\RefDivision;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DivisionController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetDivisionDatatables $getDivison, DivisionSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $divisions = $getDivison->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/Division/Index', [
            "title" => "Division | Reference Table Management",
            "additional" => [
                "list" => DivisionResource::collection($divisions),
                "filters" => $filters,
                "columns" => $getDivison->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.division.create"),
                "urlIndex" => route("panel.ref-table.division.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Division/Create', [
            "title" => "Division | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.division.store"),
                "urlIndex" => route("panel.ref-table.division.index")
            ]
        ]);
    }

    public function store(DivisionRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $division = RefDivision::create($arrData);

            return $division;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.division.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Division Success!"
            ]);
    }


    public function show(Request $request, RefDivision $division)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Division/Show', [
            "title" => "Division | Reference Table Management",
            "additional" => [
                "data" => $division,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.division.edit", $division),
                "urlIndex" => route("panel.ref-table.division.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefDivision $division)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Division/Edit', [
            "title" => "Division | Reference Table Management",
            "additional" => [
                "data" => $division,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.division.update", $division),
                "urlShow" => route("panel.ref-table.division.show", $division),
                "urlIndex" => route("panel.ref-table.division.index")
            ]
        ]);
    }

    public function update(DivisionRequest $request, RefDivision $division)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $division) {
            $arrData = $request->validated();
            $division->update($arrData);

            return $division;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.division.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Division Success!"
            ]);
    }

    public function destroy(Request $request, RefDivision $division)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($division) {
            $division->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.division.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Division Success!"
            ]);
    }
}
