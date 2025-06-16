<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ForGroup\GetForGroupDatatables;
use App\Actions\RefTable\SeoCategory\GetSeoCategoryDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ForAreaRequest;
use App\Http\Requests\ReferenceTable\ForGroupRequest;
use App\Http\Requests\ReferenceTable\ForGroupSearchRequest;
use App\Http\Requests\ReferenceTable\SeoCategoryRequest;
use App\Http\Requests\ReferenceTable\SeoCategorySearchRequest;
use App\Http\Resources\RefTable\ForAreaResource;
use App\Http\Resources\RefTable\ForGroupResource;
use App\Http\Resources\RefTable\SeoCategoryResource;
use App\Http\Resources\RefTable\SeoSectorResource;
use App\Models\RefForArea;
use App\Models\RefForCategory;
use App\Models\RefForGroup;
use App\Models\RefSeoCategory;
use App\Models\RefSeoSector;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeoCategoryController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetSeoCategoryDatatables $getSeoCategory, SeoCategorySearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();

        $categories = $getSeoCategory->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/SeoCategory/Index', [
            "title" => "SEO Category | Reference Table Management",
            "additional" => [
                "data" => SeoCategoryResource::collection($categories),
                "filters" => $filters,
                "columns" => $getSeoCategory->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.seo-category.create"),
                "urlIndex" => route("panel.ref-table.seo-category.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoCategory/Create', [
            "title" => "Create SEO Category | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "sectors" => RefSeoSector::orderBy("description", "asc")->get(),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.seo-category.store"),
                "urlIndex" => route("panel.ref-table.seo-category.index")
            ]
        ]);
    }

    public function store(SeoCategoryRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefSeoCategory::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research SEO Category Success!"
            ]);
    }


    public function show(Request $request, RefSeoCategory $category)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoCategory/Show', [
            "title" => "View SEO Category | Reference Table Management",
            "additional" => [
                "data" => $category->load("sector"),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlIndex" => route("panel.ref-table.seo-category.index"),
                "urlEdit" => route("panel.ref-table.seo-category.edit", $category)
            ]
        ]);
    }

    public function edit(Request $request, RefSeoCategory $category)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }
        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoCategory/Edit', [
            "title" => "View SEO Category | Reference Table Management",
            "additional" => [
                "data" => $category,
                "sectors" => RefSeoSector::orderBy("description", "asc")->get(),
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.seo-category.update", $category),
                "urlShow" => route("panel.ref-table.seo-category.show", $category),
                "urlIndex" => route("panel.ref-table.seo-category.index")
            ]
        ]);
    }

    public function update(SeoCategoryRequest $request, RefSeoCategory $category)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $category) {
            $arrData = $request->validated();
            $category->update($arrData);

            return $category;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research SEO Category Success!"
            ]);
    }

    public function destroy(Request $request, RefSeoCategory $category)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($category) {
            $category->delete();
            return $category;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research SEO Category Success!"
            ]);
    }
}
