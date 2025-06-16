<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ForGroup\GetForGroupDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ForAreaRequest;
use App\Http\Requests\ReferenceTable\ForGroupRequest;
use App\Http\Requests\ReferenceTable\ForGroupSearchRequest;

use App\Http\Resources\RefTable\ForAreaResource;
use App\Http\Resources\RefTable\ForGroupResource;
use App\Models\RefForArea;
use App\Models\RefForCategory;
use App\Models\RefForGroup;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ForGroupController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetForGroupDatatables $getForGroup, ForGroupSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();

        $areas = $getForGroup->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ForGroup/Index', [
            "title" => "FOR Group | Reference Table Management",
            "additional" => [
                "data" => ForGroupResource::collection($areas),
                "filters" => $filters,
                "columns" => $getForGroup->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.for-group.create"),
                "urlIndex" => route("panel.ref-table.for-group.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForGroup/Create', [
            "title" => "Create FOR Group | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "categories" => RefForCategory::orderBy("description", "asc")->get(),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.for-group.store"),
                "urlIndex" => route("panel.ref-table.for-group.index")
            ]
        ]);
    }

    public function store(ForGroupRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefForGroup::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research Group Success!"
            ]);
    }


    public function show(Request $request, RefForGroup $group)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForGroup/Show', [
            "title" => "View FOR Group | Reference Table Management",
            "additional" => [
                "data" => $group->load("category"),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlIndex" => route("panel.ref-table.for-group.index"),
                "urlEdit" => route("panel.ref-table.for-group.edit", $group)
            ]
        ]);
    }

    public function edit(Request $request, RefForGroup $group)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }
        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForGroup/Edit', [
            "title" => "View FOR Group | Reference Table Management",
            "additional" => [
                "data" => $group,
                "categories" => RefForCategory::orderBy("description", "asc")->get(),
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.for-group.update", $group),
                "urlShow" => route("panel.ref-table.for-group.show", $group),
                "urlIndex" => route("panel.ref-table.for-group.index")
            ]
        ]);
    }

    public function update(ForGroupRequest $request, RefForGroup $group)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $group) {
            $arrData = $request->validated();
            $group->update($arrData);

            return $group;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research Group Success!"
            ]);
    }

    public function destroy(Request $request, RefForGroup $group)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($group) {
            $group->delete();
            return $group;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research Group Success!"
            ]);
    }
}
