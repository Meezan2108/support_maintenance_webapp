<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ResearchType\GetResearchTypeDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ReseacrhTypeSearchRequest;
use App\Http\Requests\ReferenceTable\ResearchTypeRequest;
use App\Http\Resources\RefTable\ResearchTypeResource;
use App\Models\RefResearchType;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResearchTypeController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetResearchTypeDatatables $getType, ReseacrhTypeSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $types = $getType->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ResearchType/Index', [
            "title" => "Research Type | Reference Table Management",
            "additional" => [
                "list" => ResearchTypeResource::collection($types),
                "filters" => $filters,
                "columns" => $getType->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.research-type.create"),
                "urlIndex" => route("panel.ref-table.research-type.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchType/Create', [
            "title" => "Research Type | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.research-type.store"),
                "urlIndex" => route("panel.ref-table.research-type.index")
            ]
        ]);
    }

    public function store(ResearchTypeRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefResearchType::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-type.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research Type Success!"
            ]);
    }


    public function show(Request $request, RefResearchType $type)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchType/Show', [
            "title" => "Research Type | Reference Table Management",
            "additional" => [
                "data" => $type,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.research-type.edit", $type),
                "urlIndex" => route("panel.ref-table.research-type.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefResearchType $type)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchType/Edit', [
            "title" => "Research Type | Reference Table Management",
            "additional" => [
                "data" => $type,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.research-type.update", $type),
                "urlShow" => route("panel.ref-table.research-type.show", $type),
                "urlIndex" => route("panel.ref-table.research-type.index")
            ]
        ]);
    }

    public function update(ResearchTypeRequest $request, RefResearchType $type)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $type) {
            $arrData = $request->validated();
            $type->update($arrData);

            return $type;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-type.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research Type Success!"
            ]);
    }

    public function destroy(Request $request, RefResearchType $type)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($type) {
            $type->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-type.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research Type Success!"
            ]);
    }
}
