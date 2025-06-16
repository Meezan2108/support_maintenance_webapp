<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\SeoSector\GetSeoSectorDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\SeoSectorRequest;
use App\Http\Requests\ReferenceTable\SeoSectorSearchRequest;
use App\Http\Resources\RefTable\SeoSectorResource;
use App\Models\RefSeoSector;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeoSectorController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetSeoSectorDatatables $getSector, SeoSectorSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $sectors = $getSector->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/SeoSector/Index', [
            "title" => "SEO Sector | Reference Table Management",
            "additional" => [
                "list" => SeoSectorResource::collection($sectors),
                "filters" => $filters,
                "columns" => $getSector->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.seo-sector.create"),
                "urlIndex" => route("panel.ref-table.seo-sector.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoSector/Create', [
            "title" => "SEO Sector | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.seo-sector.store"),
                "urlIndex" => route("panel.ref-table.seo-sector.index")
            ]
        ]);
    }

    public function store(SeoSectorRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $sector = RefSeoSector::create($arrData);

            return $sector;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-sector.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of SEO Sector Success!"
            ]);
    }


    public function show(Request $request, RefSeoSector $sector)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoSector/Show', [
            "title" => "SEO Sector | Reference Table Management",
            "additional" => [
                "data" => $sector,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.seo-sector.edit", $sector),
                "urlIndex" => route("panel.ref-table.seo-sector.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefSeoSector $sector)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/SeoSector/Edit', [
            "title" => "SEO Sector | Reference Table Management",
            "additional" => [
                "data" => $sector,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.seo-sector.update", $sector),
                "urlShow" => route("panel.ref-table.seo-sector.show", $sector),
                "urlIndex" => route("panel.ref-table.seo-sector.index")
            ]
        ]);
    }

    public function update(SeoSectorRequest $request, RefSeoSector $sector)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $sector) {
            $arrData = $request->validated();
            $sector->update($arrData);

            return $sector;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-sector.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of SEO Sector Success!"
            ]);
    }

    public function destroy(Request $request, RefSeoSector $sector)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($sector) {
            $sector->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.seo-sector.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of SEO Sector Success!"
            ]);
    }
}
