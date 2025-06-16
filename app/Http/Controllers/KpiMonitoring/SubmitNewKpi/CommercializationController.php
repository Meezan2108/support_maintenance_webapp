<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateCommercializationSubmitNotification;
use App\Actions\KpiMonitoring\CreateCommercializationSubmitTask;
use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\GetCommercializationDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\CommercializationFormRequest;
use App\Http\Requests\KpiMonitoring\CommercializationSearchRequest;
use App\Http\Resources\KpiMonitoring\CommercializationResource;
use App\Models\Approvement;
use App\Models\Commercialization;
use App\Models\KpiAchievement;
use App\Models\Proposal;
use App\Models\RefOutputType;
use App\Models\User;
use App\Policies\CommercializationPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommercializationController extends Controller
{

    protected $policy;
    protected $createCommercializationSubmitNotification;
    protected $createCommercializationSubmitTask;

    public function __construct(
        CommercializationPolicy $policy,
        CreateCommercializationSubmitNotification $createCommercializationSubmitNotification,
        CreateCommercializationSubmitTask $createCommercializationSubmitTask
    ) {
        $this->policy = $policy;
        $this->createCommercializationSubmitNotification = $createCommercializationSubmitNotification;
        $this->createCommercializationSubmitTask = $createCommercializationSubmitTask;
    }

    public function index(GetCommercializationDatatables $getcommercialization, CommercializationSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $commercializations = $getcommercialization->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Commercialization/Index', [
            "title" => "List Commercialization | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => CommercializationResource::collection($commercializations),
                "filters" => $filters,
                "columns" => $getcommercialization->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.commercialization.create"),
                "urlIndex" => route("panel.commercialization.index"),
                "urlUploadBulk" => route("panel.commercialization.bulk-create")
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
            ->where("category_id", Commercialization::CATEGORY_ID)
            ->first();

        $commercialization = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Commercialization/Create', [
            "title" => "Create Commercialization | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => optional($commercialization)->load("fileable"),
                "user" => $request->user(),
                "outputTypes" => RefOutputType::all(),
                "urlStore" => route("panel.commercialization.store"),
                "urlIndex" => route("panel.commercialization.index")
            ]
        ]);
    }

    public function store(CommercializationFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $commercialization = Commercialization::create([
                "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "name" => $arrData["name"],
                "category" => $arrData["category"],
                "taker" => $arrData["taker"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "created_by" => $userId
            ]);

            $commercialization->kpiAchievement()->create([
                "title" => $commercialization->name,
                "user_id" => Auth::id(),
                "category_id" => Commercialization::CATEGORY_ID,
                "date" => $commercialization->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $commercialization,
                $requestFiles,
                $arrData,
                Commercialization::FILEABLE_FILE_CODE
            );

            $this->createCommercializationSubmitNotification->execute($commercialization);
            $this->createCommercializationSubmitTask->execute($commercialization, $user);

            return $commercialization;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.commercialization.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Commerzialization Success!"
            ]);
    }


    public function show(Request $request, Commercialization $commercialization)
    {
        if (!$this->policy->view($request->user(), $commercialization)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $commercialization->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Commercialization/Show', [
            "title" => "View Commerzialization | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $commercialization->load("kpiAchievement.user", "output_type", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $commercialization),
                "urlEdit" => route("panel.commercialization.edit", $commercialization),
                "urlIndex" => route("panel.commercialization.index")
            ]
        ]);
    }

    public function edit(Request $request, Commercialization $commercialization)
    {
        if (!$this->policy->update($request->user(), $commercialization)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Commercialization/Edit', [
            "title" => "Edit Commerzialization | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $commercialization->load("fileable"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $commercialization),
                "user" => $commercialization->kpiAchievement->user,

                "outputTypes" => RefOutputType::all(),

                "urlUpdate" => route("panel.commercialization.update", $commercialization),
                "urlShow" => route("panel.commercialization.show", $commercialization),
                "urlIndex" => route("panel.commercialization.index")
            ]
        ]);
    }

    public function update(CommercializationFormRequest $request, Commercialization $commercialization)
    {
        if (!$this->policy->update($request->user(), $commercialization)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $commercialization) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $commercialization->update([
                // "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "name" => $arrData["name"],
                "category" => $arrData["category"],
                "taker" => $arrData["taker"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "updated_id" => Auth::id()
            ]);

            $kpiAchievement = $commercialization->kpiAchievement;
            $kpiAchievement->update([
                "title" => $commercialization->name,
                "category_id" => Commercialization::CATEGORY_ID,
                "date" => $commercialization->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $commercialization->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $commercialization,
                $requestFiles,
                $arrData,
                Commercialization::FILEABLE_FILE_CODE
            );

            $this->createCommercializationSubmitNotification->execute($commercialization);
            $this->createCommercializationSubmitTask->execute($commercialization, $user);

            return $commercialization;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.commercialization.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Commercialization Success!"
            ]);
    }

    public function destroy(Request $request, Commercialization $commercialization)
    {
        if (!$this->policy->delete($request->user(), $commercialization)) {
            abort(403);
        }

        DB::transaction(function () use ($commercialization) {
            $commercialization->kpiAchievement->delete();
            $commercialization->update([
                "deleted_by" => Auth::id()
            ]);

            $commercialization->taskable()->delete();
            $commercialization->notifable()->delete();

            $commercialization->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.commercialization.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Commercialization Success!"
            ]);
    }
}
