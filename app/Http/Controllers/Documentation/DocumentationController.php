<?php

namespace App\Http\Controllers\Documentation;

use App\Actions\Documentation\CreateDocumentationNotification;
use App\Actions\Documentation\GetDocumentationDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Documentation\DocumentationFormRequest;
use App\Http\Requests\Documentation\DocumentationSearchRequest;
use App\Http\Resources\Documentation\DocumentationResource;
use App\Http\Resources\FileableResource;
use App\Models\Documentation;
use App\Models\Fileable;
use App\Models\RefOtherDocument;
use App\Policies\DocumentationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{

    protected $policy;

    public function __construct(DocumentationPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetDocumentationDatatables $getdocumentation, DocumentationSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $documentations = $getdocumentation->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Documentation/Index', [
            "title" => "Documentation",
            "additional" => [
                "list" => DocumentationResource::collection($documentations),
                "filters" => $filters,
                "columns" => $getdocumentation->getColumns(),
                "canCreate" => $this->policy->create(Auth::user()),

                "urlCreate" => route("panel.documentation.create"),
                "urlIndex" => route("panel.documentation.index")
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        $documentation = new Documentation();

        $recognition['old_files'] = FileableResource::collection($documentation->fileable->sortByDesc("id"));

        $filters = $request->session()->get('filters');

        return Inertia::render('Documentation/Create', [
            "title" => "Documentation",
            "additional" => [
                "filters" => $filters,
                "initValue" => $documentation,
                "user" => $request->user(),
                "categoryTypes" => RefOtherDocument::all(),
                "urlStore" => route("panel.documentation.store"),
                "urlIndex" => route("panel.documentation.index")
            ]
        ]);
    }

    public function store(DocumentationFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $documentation = Documentation::create([
                "user_id" => $userId = Auth::id(),
                "submission_date" => $arrData["submission_date"],
                "description" => $arrData["description"],
                "category" => $arrData["category"],
                "created_by" => $userId
            ]);

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $documentation->fileable()->create(array_merge([
                        "code_type" => Documentation::FILEABLE_FILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            (new CreateDocumentationNotification)->execute($documentation);

            return $documentation;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.documentation.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Documentation Success!"
            ]);
    }


    public function show(Request $request, Documentation $documentation)
    {
        if (!$this->policy->view($request->user(), $documentation)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        $file = $documentation->fileable->each(fn ($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('Documentation/Show', [
            "title" => "Documentation",
            "additional" => [
                "initValue" => $documentation->load("projectLeader", "fileable", "refCategory"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $documentation),
                "urlEdit" => route("panel.documentation.edit", $documentation),
                "urlIndex" => route("panel.documentation.index")
            ]
        ]);
    }

    public function edit(Request $request, Documentation $documentation)
    {
        if (!$this->policy->update($request->user(), $documentation)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        $user = $documentation->load("projectLeader");

        return Inertia::render('Documentation/Edit', [
            "title" => "Edit Documentation | Documentation",
            "additional" => [
                "initValue" => $documentation->load("fileable", "projectLeader"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $documentation),
                "user" => $documentation->projectLeader,
                "categoryTypes" => RefOtherDocument::all(),
                "urlUpdate" => route("panel.documentation.update", $documentation),
                "urlShow" => route("panel.documentation.show", $documentation),
                "urlIndex" => route("panel.documentation.index")
            ]
        ]);
    }

    public function update(DocumentationFormRequest $request, Documentation $documentation)
    {
        if (!$this->policy->update($request->user(), $documentation)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $documentation) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $documentation->update([
                "user_id" => $userId = Auth::id(),
                "submission_date" => $arrData["submission_date"],
                "description" => $arrData["description"],
                "category" => $arrData["category"],
                "updated_id" => Auth::id()
            ]);

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $documentation->fileable()->create(array_merge([
                        "code_type" => Documentation::FILEABLE_FILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = collect($arrData["old_files"])->pluck('id')->toArray();
            $documentation->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            return $documentation;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.documentation.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Documentation Success!"
            ]);
    }

    public function destroy(Request $request, Documentation $documentation)
    {
        if (!$this->policy->delete($request->user(), $documentation)) {
            abort(403);
        }

        DB::transaction(function () use ($documentation) {
            $documentation->update([
                "deleted_by" => Auth::id()
            ]);
            $documentation->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.documentation.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Documentation Success!"
            ]);
    }
}
