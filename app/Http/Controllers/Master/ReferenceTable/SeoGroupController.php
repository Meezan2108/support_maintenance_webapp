<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\SeoCategory\GetSeoCategoryDatatables;
use App\Actions\RefTable\SeoGroup\GetSeoGroupDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\SeoCategoryRequest;
use App\Http\Requests\ReferenceTable\SeoCategorySearchRequest;
use App\Http\Requests\ReferenceTable\SeoGroupRequest;
use App\Http\Requests\ReferenceTable\SeoGroupSearchRequest;
use App\Http\Resources\RefTable\SeoCategoryResource;
use App\Http\Resources\RefTable\SeoGroupResource;
use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;
use App\Models\RefSeoSector;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeoGroupController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetSeoGroupDatatables $getSeoGroup, SeoGroupSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();

        $groups = $getSeoGroup->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/SeoGroup/Index', [
            "title" => "SEO Group | Reference Table Management",
            "additional" => [
                "data" => SeoGroupResource::collection($groups),
                "filters" => $filters,
                "columns" => $getSeoGroup->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.seo-group.create"),
                "urlIndex" => route("panel.ref-table.seo-group.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoGroup/Create', [
            "title" => "Create SEO Group | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "categories" => RefSeoCategory::orderBy("description", "asc")->get(),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.seo-group.store"),
                "urlIndex" => route("panel.ref-table.seo-group.index")
            ]
        ]);
    }

    public function store(SeoGroupRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefSeoGroup::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research SEO Group Success!"
            ]);
    }


    public function show(Request $request, RefSeoGroup $group)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoGroup/Show', [
            "title" => "View SEO Group | Reference Table Management",
            "additional" => [
                "data" => $group->load("category"),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlIndex" => route("panel.ref-table.seo-group.index"),
                "urlEdit" => route("panel.ref-table.seo-group.edit", $group)
            ]
        ]);
    }

    public function edit(Request $request, RefSeoGroup $group)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }
        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoGroup/Edit', [
            "title" => "View SEO Group | Reference Table Management",
            "additional" => [
                "data" => $group,
                "categories" => RefSeoCategory::orderBy("description", "asc")->get(),
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.seo-group.update", $group),
                "urlShow" => route("panel.ref-table.seo-group.show", $group),
                "urlIndex" => route("panel.ref-table.seo-group.index")
            ]
        ]);
    }

    public function update(SeoGroupRequest $request, RefSeoGroup $group)
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

        return redirect()->route("panel.ref-table.seo-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research SEO Group Success!"
            ]);
    }

    public function destroy(Request $request, RefSeoGroup $group)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($group) {
            $group->delete();
            return $group;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-group.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research SEO Group Success!"
            ]);
    }
}
