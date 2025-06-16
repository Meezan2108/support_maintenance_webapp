<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabSubmitNotification;
use App\Actions\KpiMonitoring\CreateAnalyticalServiceLabSubmitTask;
use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\GetAnalyticalServiceLabDatatables;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\AnalyticalServiceLabFormRequest;
use App\Http\Requests\KpiMonitoring\AnalyticalServiceLabSearchRequest;
use App\Http\Resources\KpiMonitoring\AnalyticalServiceLabResource;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\User;
use App\Policies\AnalyticalServiceLabPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticalServiceLabController extends Controller
{

    protected $policy;
    protected $createAnalyticalServiceLabSubmitNotification;
    protected $createAnalyticalServiceLabSubmitTask;

    public function __construct(
        AnalyticalServiceLabPolicy $policy,
        CreateAnalyticalServiceLabSubmitNotification $createAnalyticalServiceLabSubmitNotification,
        CreateAnalyticalServiceLabSubmitTask $createAnalyticalServiceLabSubmitTask
    ) {
        $this->policy = $policy;
        $this->createAnalyticalServiceLabSubmitNotification = $createAnalyticalServiceLabSubmitNotification;
        $this->createAnalyticalServiceLabSubmitTask = $createAnalyticalServiceLabSubmitTask;
    }

    public function index(GetAnalyticalServiceLabDatatables $getasl, AnalyticalServiceLabSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $asl = $getasl->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/AnalyticalServiceLab/Index', [
            "title" => "List Analytical Service Lab | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => AnalyticalServiceLabResource::collection($asl),
                "filters" => $filters,
                "columns" => $getasl->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.analytical-service-lab.create"),
                "urlIndex" => route("panel.analytical-service-lab.index"),
                "urlUploadBulk" => route("panel.analytical-service-lab.bulk-create")
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
            ->where("category_id", AnalyticalServiceLab::CATEGORY_ID)
            ->first();

        $asl = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/AnalyticalServiceLab/Create', [
            "title" => "Create AnalyticalServiceLab | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => optional($asl)->load("fileable"),
                "user" => $request->user(),
                "urlStore" => route("panel.analytical-service-lab.store"),
                "urlIndex" => route("panel.analytical-service-lab.index")
            ]
        ]);
    }

    public function store(AnalyticalServiceLabFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());
            $userId = $user->id;

            $asl = AnalyticalServiceLab::create([
                "user_id" => $userId,
                "date" => DateHelper::calcDateByQuarter($arrData['year'], $arrData['quarter']),
                "year" => $arrData["year"],
                "quarter" => $arrData["quarter"],
                "no_sample" => $arrData["no_sample"],
                "no_analysis" => $arrData["no_analysis"],
                "no_analysis_protocol" => $arrData["no_analysis_protocol"],
                "created_by" => $userId
            ]);

            $asl->kpiAchievement()->create([
                "title" => 'Q' . $asl->quarter . '-' . $asl->year,
                "user_id" => Auth::id(),
                "category_id" => AnalyticalServiceLab::CATEGORY_ID,
                "date" => $asl->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $asl,
                $requestFiles,
                $arrData,
                AnalyticalServiceLab::FILEABLE_FILE_CODE
            );

            $this->createAnalyticalServiceLabSubmitNotification->execute($asl);
            $this->createAnalyticalServiceLabSubmitTask->execute($asl, $user);

            return $asl;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.analytical-service-lab.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Analytical Service Lab Success!"
            ]);
    }

    public function show(Request $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->view($request->user(), $analytical)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $analytical->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/AnalyticalServiceLab/Show', [
            "title" => "View Analytical Service Lab | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $analytical->load("kpiAchievement.user", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $analytical),
                "urlEdit" => route("panel.analytical-service-lab.edit", $analytical),
                "urlIndex" => route("panel.analytical-service-lab.index")
            ]
        ]);
    }

    public function edit(Request $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->update($request->user(), $analytical)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/AnalyticalServiceLab/Edit', [
            "title" => "Edit Analytical Service Lab | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $analytical->load("fileable"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $analytical),
                "user" => $analytical->kpiAchievement->user,
                "urlUpdate" => route("panel.analytical-service-lab.update", $analytical),
                "urlShow" => route("panel.analytical-service-lab.show", $analytical),
                "urlIndex" => route("panel.analytical-service-lab.index")
            ]
        ]);
    }

    public function update(AnalyticalServiceLabFormRequest $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->update($request->user(), $analytical)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $analytical) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $analytical->update([
                "date" => DateHelper::calcDateByQuarter($arrData['year'], $arrData['quarter']),
                "year" => $arrData["year"],
                "quarter" => $arrData["quarter"],
                "no_sample" => $arrData["no_sample"],
                "no_analysis" => $arrData["no_analysis"],
                "no_analysis_protocol" => $arrData["no_analysis_protocol"],
                "updated_id" => $user->id
            ]);

            $kpiAchievement = $analytical->kpiAchievement;
            $kpiAchievement->update([
                "title" => 'Q' . $analytical->quarter . '-' . $analytical->year,
                "category_id" => AnalyticalServiceLab::CATEGORY_ID,
                "date" => $analytical->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $analytical->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $analytical,
                $requestFiles,
                $arrData,
                AnalyticalServiceLab::FILEABLE_FILE_CODE
            );

            $this->createAnalyticalServiceLabSubmitNotification->execute($analytical);
            $this->createAnalyticalServiceLabSubmitTask->execute($analytical, $user);

            return $analytical;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.analytical-service-lab.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Analytical Service Lab Success!"
            ]);
    }

    public function destroy(Request $request, AnalyticalServiceLab $analytical)
    {
        if (!$this->policy->delete($request->user(), $analytical)) {
            abort(403);
        }

        DB::transaction(function () use ($analytical) {
            $analytical->kpiAchievement->delete();
            $analytical->update([
                "deleted_by" => Auth::id()
            ]);

            $analytical->taskable()->delete();
            $analytical->notifable()->delete();

            $analytical->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.analytical-service-lab.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Analytical Service Lab Success!"
            ]);
    }
}
