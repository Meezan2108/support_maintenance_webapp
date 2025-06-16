<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\CreateImportedGermplasmSubmitNotification;
use App\Actions\KpiMonitoring\CreateImportedGermplasmSubmitTask;
use App\Actions\KpiMonitoring\GetImportedGermplasmDatatables;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\ImportedGermplasmFormRequest;
use App\Http\Requests\KpiMonitoring\ImportedGermplasmSearchRequest;
use App\Http\Resources\KpiMonitoring\ImportedGermplasmResource;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\KpiAchievement;
use App\Models\User;
use App\Policies\ImportedGermplasmPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ImportedGermplasmController extends Controller
{

    protected $policy;
    protected $createImportedGermplasmSubmitNotification;
    protected $createImportedGermplasmSubmitTask;

    public function __construct(
        ImportedGermplasmPolicy $policy,
        CreateImportedGermplasmSubmitNotification $createImportedGermplasmSubmitNotification,
        CreateImportedGermplasmSubmitTask $createImportedGermplasmSubmitTask
    ) {
        $this->policy = $policy;
        $this->createImportedGermplasmSubmitNotification = $createImportedGermplasmSubmitNotification;
        $this->createImportedGermplasmSubmitTask = $createImportedGermplasmSubmitTask;
    }

    public function index(GetImportedGermplasmDatatables $getgermplasm, ImportedGermplasmSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $germplasm = $getgermplasm->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/ImportedGermplasm/Index', [
            "title" => "List Imported Germplasm | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => ImportedGermplasmResource::collection($germplasm),
                "filters" => $filters,
                "columns" => $getgermplasm->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.imported-germplasm.create"),
                "urlIndex" => route("panel.imported-germplasm.index"),
                "urlUploadBulk" => route("panel.imported-germplasm.bulk-create")
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
            ->where("category_id", ImportedGermplasm::CATEGORY_ID)
            ->first();

        $germplasm = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/ImportedGermplasm/Create', [
            "title" => "Create Imported Germplasm | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => optional($germplasm)->load("fileable"),
                "user" => $request->user(),
                "urlStore" => route("panel.imported-germplasm.store"),
                "urlIndex" => route("panel.imported-germplasm.index")
            ]
        ]);
    }

    public function store(ImportedGermplasmFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $germplasm = ImportedGermplasm::create([
                "user_id" => $userId = Auth::id(),
                "date" => DateHelper::calcDateByQuarter($arrData['year'], $arrData['quarter']),
                "year" => $arrData["year"],
                "quarter" => $arrData["quarter"],
                "no_germplasm" => $arrData["no_germplasm"],
                "created_by" => $userId
            ]);

            $germplasm->kpiAchievement()->create([
                "title" => 'Q' . $germplasm->quarter . '-' . $germplasm->year,
                "user_id" => Auth::id(),
                "category_id" => ImportedGermplasm::CATEGORY_ID,
                "date" => $germplasm->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $germplasm,
                $requestFiles,
                $arrData,
                ImportedGermplasm::FILEABLE_FILE_CODE
            );

            $this->createImportedGermplasmSubmitNotification->execute($germplasm);
            $this->createImportedGermplasmSubmitTask->execute($germplasm, $user);

            return $germplasm;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.imported-germplasm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Imported Germplasm Success!"
            ]);
    }


    public function show(Request $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->view($request->user(), $germplasm)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $germplasm->fileable->each(fn($file) => $file->file_url = $file->url);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/ImportedGermplasm/Show', [
            "title" => "View Imported Germplasm | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $germplasm->load("kpiAchievement.user", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $germplasm),
                "urlEdit" => route("panel.imported-germplasm.edit", $germplasm),
                "urlIndex" => route("panel.imported-germplasm.index")
            ]
        ]);
    }

    public function edit(Request $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->update($request->user(), $germplasm)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/ImportedGermplasm/Edit', [
            "title" => "Edit Imported Germplasm | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $germplasm->load("fileable"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $germplasm),
                "user" => $germplasm->kpiAchievement->user,

                "urlUpdate" => route("panel.imported-germplasm.update", $germplasm),
                "urlShow" => route("panel.imported-germplasm.show", $germplasm),
                "urlIndex" => route("panel.imported-germplasm.index")
            ]
        ]);
    }

    public function update(ImportedGermplasmFormRequest $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->update($request->user(), $germplasm)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $germplasm) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $germplasm->update([
                "date" => DateHelper::calcDateByQuarter($arrData['year'], $arrData['quarter']),
                "year" => $arrData["year"],
                "quarter" => $arrData["quarter"],
                "no_germplasm" => $arrData["no_germplasm"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "updated_id" => Auth::id()
            ]);

            $kpiAchievement = $germplasm->kpiAchievement;
            $kpiAchievement->update([
                "title" => 'Q' . $germplasm->quarter . '-' . $germplasm->year,
                "category_id" => ImportedGermplasm::CATEGORY_ID,
                "date" => $germplasm->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $germplasm->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $germplasm,
                $requestFiles,
                $arrData,
                ImportedGermplasm::FILEABLE_FILE_CODE
            );

            $this->createImportedGermplasmSubmitNotification->execute($germplasm);
            $this->createImportedGermplasmSubmitTask->execute($germplasm, $user);

            return $germplasm;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.imported-germplasm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Imported Germplasm Success!"
            ]);
    }

    public function destroy(Request $request, ImportedGermplasm $germplasm)
    {
        if (!$this->policy->delete($request->user(), $germplasm)) {
            abort(403);
        }

        DB::transaction(function () use ($germplasm) {
            $germplasm->kpiAchievement->delete();
            $germplasm->update([
                "deleted_by" => Auth::id()
            ]);

            $germplasm->taskable()->delete();
            $germplasm->notifable()->delete();

            $germplasm->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.imported-germplasm.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Imported Germplasm Success!"
            ]);
    }
}
