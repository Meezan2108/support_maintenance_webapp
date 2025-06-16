<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ForCategory\GetForCategoryDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ForCategoryRequest;
use App\Http\Requests\ReferenceTable\ForCategorySearchRequest;
use App\Http\Resources\RefTable\ForCategoryResource;
use App\Models\refFORCategory;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ForCategoryController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetForCategoryDatatables $getCategory, ForCategorySearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $categories = $getCategory->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ForCategory/Index', [
            "title" => "FOR Category | Reference Table Management",
            "additional" => [
                "list" => ForCategoryResource::collection($categories),
                "filters" => $filters,
                "columns" => $getCategory->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.for-category.create"),
                "urlIndex" => route("panel.ref-table.for-category.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForCategory/Create', [
            "title" => "FOR Category | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.for-category.store"),
                "urlIndex" => route("panel.ref-table.for-category.index")
            ]
        ]);
    }

    public function store(ForCategoryRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = refFORCategory::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research Category Success!"
            ]);
    }


    public function show(Request $request, refFORCategory $category)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForCategory/Show', [
            "title" => "FOR Category | Reference Table Management",
            "additional" => [
                "data" => $category,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.for-category.edit", $category),
                "urlIndex" => route("panel.ref-table.for-category.index")
            ]
            
        ]);
    }

    public function edit(Request $request, refFORCategory $category)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ForCategory/Edit', [
            "title" => "FOR Category | Reference Table Management",
            "additional" => [
                "data" => $category,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.for-category.update", $category),
                "urlShow" => route("panel.ref-table.for-category.show", $category),
                "urlIndex" => route("panel.ref-table.for-category.index")
            ]
        ]);
    }

    public function update(ForCategoryRequest $request, refFORCategory $category)
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

        return redirect()->route("panel.ref-table.for-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research Category Success!"
            ]);
    }

    public function destroy(Request $request, refFORCategory $category)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($category) {
            $category->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.for-category.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research Category Success!"
            ]);
    }
}
