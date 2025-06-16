<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\Patent\GetPatentDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\PatentRequest;
use App\Http\Requests\ReferenceTable\PatentSearchRequest;
use App\Http\Resources\RefTable\PatentResource;
use App\Models\RefPatent;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PatentController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetPatentDatatables $getPatent, PatentSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $patents = $getPatent->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/Patent/Index', [
            "title" => "Intellectual Property | Reference Table Management",
            "additional" => [
                "list" => PatentResource::collection($patents),
                "filters" => $filters,
                "columns" => $getPatent->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.patent.create"),
                "urlIndex" => route("panel.ref-table.patent.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Patent/Create', [
            "title" => "Intellectual Property | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.patent.store"),
                "urlIndex" => route("panel.ref-table.patent.index")
            ]
        ]);
    }

    public function store(PatentRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $patent = RefPatent::create($arrData);

            return $patent;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.patent.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Intellectual Property Success!"
            ]);
    }


    public function show(Request $request, RefPatent $patent)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Patent/Show', [
            "title" => "Intellectual Property | Reference Table Management",
            "additional" => [
                "data" => $patent,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.patent.edit", $patent),
                "urlIndex" => route("panel.ref-table.patent.index")
            ]

        ]);
    }

    public function edit(Request $request, RefPatent $patent)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/Patent/Edit', [
            "title" => "Intellectual Property | Reference Table Management",
            "additional" => [
                "data" => $patent,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.patent.update", $patent),
                "urlShow" => route("panel.ref-table.patent.show", $patent),
                "urlIndex" => route("panel.ref-table.patent.index")
            ]
        ]);
    }

    public function update(PatentRequest $request, RefPatent $patent)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $patent) {
            $arrData = $request->validated();
            $patent->update($arrData);

            return $patent;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.patent.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Intellectual Property Success!"
            ]);
    }

    public function destroy(Request $request, RefPatent $patent)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($patent) {
            $patent->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.patent.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Intellectual Property Success!"
            ]);
    }
}
