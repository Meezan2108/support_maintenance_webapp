<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ForArea\GetForArea;
use App\Actions\RefTable\ForArea\GetForAreaDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ForAreaRequest;
use App\Http\Requests\ReferenceTable\ForAreaSearchRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\RefTable\ForAreaResource;
use App\Models\RefForArea;
use App\Models\RefForGroup;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ForAreaController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetForAreaDatatables $getForArea, ForAreaSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();

        $areas = $getForArea->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ForArea/Index', [
            "title" => "FOR Area | Reference Table Management",
            "additional" => [
                "areas" => ForAreaResource::collection($areas),
                "filters" => $filters,
                "columns" => $getForArea->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.for-area.create"),
                "urlIndex" => route("panel.ref-table.for-area.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForArea/Create', [
            "title" => "Create FOR Area | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "groups" => RefForGroup::orderBy("description_plain", "asc")
                    ->selectRaw("id, description as description_plain, code + ' - ' + description as description")->get(),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.for-area.store"),
                "urlIndex" => route("panel.ref-table.for-area.index")
            ]
        ]);
    }

    public function store(ForAreaRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefForArea::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research Area Success!"
            ]);
    }


    public function show(Request $request, RefForArea $area)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForArea/Show', [
            "title" => "View FOR Area | Reference Table Management",
            "additional" => [
                "area" => $area->load("group"),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlIndex" => route("panel.ref-table.for-area.index"),
                "urlEdit" => route("panel.ref-table.for-area.edit", $area)
            ]
        ]);
    }

    public function edit(Request $request, RefForArea $area)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }
        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForArea/Edit', [
            "title" => "Edit FOR Area | Reference Table Management",
            "additional" => [
                "area" => $area,
                "groups" => RefForGroup::orderBy("description", "asc")->get(),
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.for-area.update", $area),
                "urlShow" => route("panel.ref-table.for-area.show", $area),
                "urlIndex" => route("panel.ref-table.for-area.index")
            ]
        ]);
    }

    public function update(ForAreaRequest $request, RefForArea $area)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $area) {
            $arrData = $request->validated();
            $area->update($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research Area Success!"
            ]);
    }

    public function destroy(Request $request, RefForArea $area)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($area) {
            $area->delete();
            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research Area Success!"
            ]);
    }
}
