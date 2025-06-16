<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\SeoArea\GetSeoAreaDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\SeoAreaRequest;
use App\Http\Requests\ReferenceTable\SeoAreaSearchRequest;
use App\Http\Resources\RefTable\SeoAreaResource;
use App\Models\RefSeoArea;
use App\Models\RefSeoGroup;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeoAreaController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetSeoAreaDatatables $getSeoArea, SeoAreaSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();

        $areas = $getSeoArea->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/SeoArea/Index', [
            "title" => "SEO Area | Reference Table Management",
            "additional" => [
                "data" => SeoAreaResource::collection($areas),
                "filters" => $filters,
                "columns" => $getSeoArea->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.seo-area.create"),
                "urlIndex" => route("panel.ref-table.seo-area.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoArea/Create', [
            "title" => "Create SEO Area | Reference Table Management",
            "additional" => [
                "filters" => $filters,
                "groups" => RefSeoGroup::orderBy("description", "asc")->get(),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.seo-area.store"),
                "urlIndex" => route("panel.ref-table.seo-area.index")
            ]
        ]);
    }

    public function store(SeoAreaRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefSeoArea::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research SEO Area Success!"
            ]);
    }


    public function show(Request $request, RefSeoArea $area)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoArea/Show', [
            "title" => "View SEO Area | Reference Table Management",
            "additional" => [
                "data" => $area->load("group"),
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlIndex" => route("panel.ref-table.seo-area.index"),
                "urlEdit" => route("panel.ref-table.seo-area.edit", $area)
            ]
        ]);
    }

    public function edit(Request $request, RefSeoArea $area)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }
        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoArea/Edit', [
            "title" => "View SEO Area | Reference Table Management",
            "additional" => [
                "data" => $area,
                "groups" => RefSeoGroup::orderBy("description", "asc")->get(),
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.seo-area.update", $area),
                "urlShow" => route("panel.ref-table.seo-area.show", $area),
                "urlIndex" => route("panel.ref-table.seo-area.index")
            ]
        ]);
    }

    public function update(SeoAreaRequest $request, RefSeoArea $area)
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

        return redirect()->route("panel.ref-table.seo-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research SEO Area Success!"
            ]);
    }

    public function destroy(Request $request, RefSeoArea $area)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($area) {
            $area->delete();
            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-area.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research SEO Area Success!"
            ]);
    }
}
