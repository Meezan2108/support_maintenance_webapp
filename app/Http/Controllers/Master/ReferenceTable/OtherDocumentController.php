<?php

namespace App\Http\Controllers\Master\ReferenceTable;

use App\Actions\RefTable\OtherDocument\GetOtherDocumentDatatables;
use App\Actions\RefTable\Patent\GetPatentDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferenceTable\OtherDocumentRequest;
use App\Http\Requests\ReferenceTable\OtherDocumentSearchRequest;
use App\Http\Requests\ReferenceTable\PatentRequest;
use App\Http\Requests\ReferenceTable\PatentSearchRequest;
use App\Http\Resources\RefTable\OtherDocumentResource;
use App\Http\Resources\RefTable\PatentResource;
use App\Models\RefOtherDocument;
use App\Models\RefPatent;
use App\Policies\RefTablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OtherDocumentController extends Controller
{
    protected $refTablePolicy;

    public function __construct(RefTablePolicy $refTablePolicy)
    {
        $this->refTablePolicy = $refTablePolicy;
    }

    public function index(GetOtherDocumentDatatables $getDocument, OtherDocumentSearchRequest $request)
    {
        if (!$this->refTablePolicy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $documents = $getDocument->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/RefTable/OtherDocument/Index', [
            "title" => "Other Document | Reference Table Management",
            "additional" => [
                "list" => OtherDocumentResource::collection($documents),
                "filters" => $filters,
                "columns" => $getDocument->getColumns(),
                "canCreate" => $this->refTablePolicy->create(Auth::user()),

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlCreate" => route("panel.ref-table.other-document.create"),
                "urlIndex" => route("panel.ref-table.other-document.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/OtherDocument/Create', [
            "title" => "Other Document | Reference Table Management",
            "additional" => [
                "filters" => $filters,

                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlStore" => route("panel.ref-table.other-document.store"),
                "urlIndex" => route("panel.ref-table.other-document.index")
            ]
        ]);
    }

    public function store(OtherDocumentRequest $request)
    {
        if (!$this->refTablePolicy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();
            $document = RefOtherDocument::create($arrData);

            return $document;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.other-document.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Field of Other Document Success!"
            ]);
    }


    public function show(Request $request, RefOtherDocument $document)
    {
        if (!$this->refTablePolicy->view($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/OtherDocument/Show', [
            "title" => "Other Document | Reference Table Management",
            "additional" => [
                "data" => $document,
                "filters" => $filters,
                "canEdit" => $this->refTablePolicy->update($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlEdit" => route("panel.ref-table.other-document.edit", $document),
                "urlIndex" => route("panel.ref-table.other-document.index")
            ]
            
        ]);
    }

    public function edit(Request $request, RefOtherDocument $document)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('Master/RefTable/OtherDocument/Edit', [
            "title" => "Other Document | Reference Table Management",
            "additional" => [
                "data" => $document,
                "filters" => $filters,
                "canView" => $this->refTablePolicy->view($request->user()),
    
                "urlRefTableIndex" => route("panel.ref-table.index"),
                "urlUpdate" => route("panel.ref-table.other-document.update", $document),
                "urlShow" => route("panel.ref-table.other-document.show", $document),
                "urlIndex" => route("panel.ref-table.other-document.index")
            ]
        ]);
    }

    public function update(OtherDocumentRequest $request, RefOtherDocument $document)
    {
        if (!$this->refTablePolicy->update($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $document) {
            $arrData = $request->validated();
            $document->update($arrData);

            return $document;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.other-document.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Field of Other Document Success!"
            ]);
    }

    public function destroy(Request $request, RefOtherDocument $document)
    {
        if (!$this->refTablePolicy->delete($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($document) {
            $document->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ref-table.other-document.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Field of Other Document Success!"
            ]);
    }
}
