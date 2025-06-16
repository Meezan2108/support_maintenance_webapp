<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\Division\GetDivisionDatatables;
use App\Actions\RefTable\ProjectStatus\GetProjectStatusDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\DivisionRequest;
use App\Http\Requests\ReferenceTable\DivisionSearchRequest;
use App\Http\Requests\ReferenceTable\ProjectStatusRequest;
use App\Http\Requests\ReferenceTable\ProjectStatusSearchRequest;
use App\Http\Resources\RefTable\DivisionResource;
use App\Http\Resources\RefTable\ProjectStatusResource;
use App\Models\RefDivision;
use App\Models\RefStatusProject;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProjectStatusController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetProjectStatusDatatables $getStatus, ProjectStatusSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $status = $getStatus->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ProjectStatus/Index', [
            "title" => "Project Status | Reference Table Management",
            "additional" => [
                "list" => ProjectStatusResource::collection($status),
                "filters" => $filters,
                "columns" => $getStatus->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.project-status.create"),
                "urlIndex" => route("panel.ref-table.project-status.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ProjectStatus/Create', [
            "title" => "Project Status | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.project-status.store"),
                "urlIndex" => route("panel.ref-table.project-status.index")
            ]
        ]);
    }

    public function store(ProjectStatusRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $status = RefStatusProject::create($arrData);

            return $status;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.project-status.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Project Status Success!"
            ]);
    }


    public function show(Request $request, RefStatusProject $status)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ProjectStatus/Show', [
            "title" => "Project Status | Reference Table Management",
            "additional" => [
                "data" => $status,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.project-status.edit", $status),
                "urlIndex" => route("panel.ref-table.project-status.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefStatusProject $status)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ProjectStatus/Edit', [
            "title" => "Project Status | Reference Table Management",
            "additional" => [
                "data" => $status,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.project-status.update", $status),
                "urlShow" => route("panel.ref-table.project-status.show", $status),
                "urlIndex" => route("panel.ref-table.project-status.index")
            ]
        ]);
    }

    public function update(ProjectStatusRequest $request, RefStatusProject $status)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $status) {
            $arrData = $request->validated();
            $status->update($arrData);

            return $status;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.project-status.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Project Status Success!"
            ]);
    }

    public function destroy(Request $request, RefStatusProject $status)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($status) {
            $status->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.project-status.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Project Status Success!"
            ]);
    }
}
