<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\ResearchCluster\GetResearchClusterDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\ReseacrhClusterSearchRequest;
use App\Http\Requests\ReferenceTable\ResearchClusterRequest;
use App\Http\Resources\RefTable\ResearchClusterResource;
use App\Models\RefResearchCluster;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResearchClusterController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetResearchClusterDatatables $getCluster, ReseacrhClusterSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $clusters = $getCluster->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/ResearchCluster/Index', [
            "title" => "Research Cluster | Reference Table Management",
            "additional" => [
                "list" => ResearchClusterResource::collection($clusters),
                "filters" => $filters,
                "columns" => $getCluster->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.research-cluster.create"),
                "urlIndex" => route("panel.ref-table.research-cluster.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchCluster/Create', [
            "title" => "Research Cluster | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.research-cluster.store"),
                "urlIndex" => route("panel.ref-table.research-cluster.index")
            ]
        ]);
    }

    public function store(ResearchClusterRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $area = RefResearchCluster::create($arrData);

            return $area;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-cluster.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Research Cluster Success!"
            ]);
    }


    public function show(Request $request, RefResearchCluster $cluster)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchCluster/Show', [
            "title" => "Research Cluster | Reference Table Management",
            "additional" => [
                "data" => $cluster,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.research-cluster.edit", $cluster),
                "urlIndex" => route("panel.ref-table.research-cluster.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefResearchCluster $cluster)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/ResearchCluster/Edit', [
            "title" => "Research Cluster | Reference Table Management",
            "additional" => [
                "data" => $cluster,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.research-cluster.update", $cluster),
                "urlShow" => route("panel.ref-table.research-cluster.show", $cluster),
                "urlIndex" => route("panel.ref-table.research-cluster.index")
            ]
        ]);
    }

    public function update(ResearchClusterRequest $request, RefResearchCluster $cluster)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $cluster) {
            $arrData = $request->validated();
            $cluster->update($arrData);

            return $cluster;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-cluster.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Research Cluster Success!"
            ]);
    }

    public function destroy(Request $request, RefResearchCluster $cluster)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($cluster) {
            $cluster->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.research-cluster.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Research Cluster Success!"
            ]);
    }
}
