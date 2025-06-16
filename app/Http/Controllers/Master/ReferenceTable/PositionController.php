<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\Position\GetPositionDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\PositionRequest;
use App\Http\Requests\ReferenceTable\PositionSearchRequest;
use App\Http\Resources\RefTable\PositionResource;
use App\Models\RefPosition;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PositionController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetPositionDatatables $getPosition, PositionSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $positions = $getPosition->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/Position/Index', [
            "title" => "Position | Reference Table Management",
            "additional" => [
                "list" => PositionResource::collection($positions),
                "filters" => $filters,
                "columns" => $getPosition->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.position.create"),
                "urlIndex" => route("panel.ref-table.position.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Position/Create', [
            "title" => "Position | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.position.store"),
                "urlIndex" => route("panel.ref-table.position.index")
            ]
        ]);
    }

    public function store(PositionRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $position = RefPosition::create($arrData);

            return $position;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.position.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Position Success!"
            ]);
    }


    public function show(Request $request, RefPosition $position)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Position/Show', [
            "title" => "Position | Reference Table Management",
            "additional" => [
                "data" => $position,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.position.edit", $position),
                "urlIndex" => route("panel.ref-table.position.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefPosition $position)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Position/Edit', [
            "title" => "Position | Reference Table Management",
            "additional" => [
                "data" => $position,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.position.update", $position),
                "urlShow" => route("panel.ref-table.position.show", $position),
                "urlIndex" => route("panel.ref-table.position.index")
            ]
        ]);
    }

    public function update(PositionRequest $request, RefPosition $position)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $position) {
            $arrData = $request->validated();
            $position->update($arrData);

            return $position;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.position.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Position Success!"
            ]);
    }

    public function destroy(Request $request, RefPosition $position)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($position) {
            $position->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.position.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Position Success!"
            ]);
    }
}
