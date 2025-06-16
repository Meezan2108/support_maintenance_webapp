<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\CreatePublicationSubmitNotification;
use App\Actions\KpiMonitoring\CreatePublicationSubmitTask;
use App\Actions\KpiMonitoring\CreateResearcherInvolved;
use App\Actions\KpiMonitoring\GetPublicationsDatatables;

use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\PublicationFormRequest;
use App\Http\Requests\KpiMonitoring\PublicationSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\KpiMonitoring\PublicationResource;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\RefPubType;
use App\Models\User;
use App\Policies\PublicationPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PublicationsController extends Controller
{

    protected $policy;
    protected $createPublicationSubmitNotification;
    protected $createPublicationSubmitTask;

    public function __construct(
        PublicationPolicy $policy,
        CreatePublicationSubmitNotification $createPublicationSubmitNotification,
        CreatePublicationSubmitTask $createPublicationSubmitTask
    ) {
        $this->policy = $policy;
        $this->createPublicationSubmitNotification = $createPublicationSubmitNotification;
        $this->createPublicationSubmitTask = $createPublicationSubmitTask;
    }

    public function index(GetPublicationsDatatables $getDivison, PublicationSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $divisions = $getDivison->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Publications/Index', [
            "title" => "List Publications | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => PublicationResource::collection($divisions),
                "filters" => $filters,
                "columns" => $getDivison->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.publications.create"),
                "urlIndex" => route("panel.publications.index"),
                "urlUploadBulk" => route("panel.publications.bulk-create"),
            ]
        ]);
    }

    public function create(Request $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        $kpiAchievement = KpiAchievement::with("reff")
            ->where("user_id", Auth::id())
            ->where("approval_status", Approvement::STATUS_DRAFT)
            ->where("category_id", Publication::CATEGORY_ID)
            ->first();

        $publication = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');
        $publication['old_files'] = $publication
            ? FileableResource::collection(
                $publication->fileable->sortByDesc("id")
            )
            : [];

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Publications/Create', [
            "title" => "Create Publications | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $publication,
                "user" => $request->user(),
                "publicationTypes" => RefPubType::all(),
                "urlStore" => route("panel.publications.store"),
                "urlIndex" => route("panel.publications.index")
            ]
        ]);
    }

    public function store(PublicationFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $publication = Publication::create([
                "user_id" => $user->id,
                "date_published" => $arrData["date_published"],
                "title" => $arrData["title"],
                "ref_pub_type_id" => $arrData["ref_pub_type_id"],
                "publisher" => $arrData["publisher"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "created_by" => $user->id
            ]);

            $kpiAchievement = $publication->kpiAchievement()->create([
                "title" => $publication->title,
                "user_id" => $user->id,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $publication,
                $requestFiles,
                $arrData,
                Publication::FILEABLE_FILE_CODE
            );

            (new CreateResearcherInvolved)->execute($publication, $kpiAchievement, $arrData["researchers"] ?? []);

            $this->createPublicationSubmitNotification->execute($publication);
            $this->createPublicationSubmitTask->execute($publication, $user);


            return $publication;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.publications.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Publications Success!"
            ]);
    }


    public function show(Request $request, Publication $publication)
    {
        if (!$this->policy->view($request->user(), $publication)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $publication->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Publications/Show', [
            "title" => "View Publications | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $publication->load("kpiAchievement.user", "type", "proposal", "researcherInvolved"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $publication),
                "urlEdit" => route("panel.publications.edit", $publication),
                "urlIndex" => route("panel.publications.index")
            ]
        ]);
    }

    public function edit(Request $request, Publication $publication)
    {
        if (!$this->policy->update($request->user(), $publication)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $projectNumbers = Proposal::query()
            ->where("user_id", Auth::id())
            ->whereIn("project_status", [Proposal::STATUS_PRJ_ON_GOING, Proposal::STATUS_PRJ_COMPLETED])
            ->get(["id", "project_number"])
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->project_number
                ];
            });

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Publications/Edit', [
            "title" => "Edit Publications | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $publication->load("fileable", "researcherInvolved"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $publication),
                "user" => $publication->kpiAchievement->user,
                "projectNumbers" => $projectNumbers,

                "publicationTypes" => RefPubType::all(),

                "urlUpdate" => route("panel.publications.update", $publication),
                "urlShow" => route("panel.publications.show", $publication),
                "urlIndex" => route("panel.publications.index")
            ]
        ]);
    }

    public function update(PublicationFormRequest $request, Publication $publication)
    {
        if (!$this->policy->update($request->user(), $publication)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $publication) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $publication->update([
                "date_published" => $arrData["date_published"],
                "title" => $arrData["title"],
                "ref_pub_type_id" => $arrData["ref_pub_type_id"],
                "publisher" => $arrData["publisher"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "updated_id" => $user->id
            ]);

            $kpiAchievement = $publication->kpiAchievement;

            $kpiAchievement->update([
                "title" => $publication->title,
                "category_id" => Publication::CATEGORY_ID,
                "date" => $publication->date_published,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $publication->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $publication,
                $requestFiles,
                $arrData,
                Publication::FILEABLE_FILE_CODE
            );

            (new CreateResearcherInvolved)->execute($publication, $kpiAchievement, $arrData["researchers"] ?? []);

            $this->createPublicationSubmitNotification->execute($publication);
            $this->createPublicationSubmitTask->execute($publication, $user);

            return $publication;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.publications.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Publication Success!"
            ]);
    }

    public function destroy(Request $request, Publication $publication)
    {
        if (!$this->policy->delete($request->user(), $publication)) {
            abort(403);
        }

        DB::transaction(function () use ($publication) {
            $publication->kpiAchievement->delete();
            $publication->update([
                "deleted_by" => Auth::id()
            ]);

            $publication->taskable()->delete();
            $publication->notifable()->delete();
            $publication->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.publications.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Publication Success!"
            ]);
    }
}
