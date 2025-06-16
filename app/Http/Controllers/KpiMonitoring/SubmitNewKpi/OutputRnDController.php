<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\CreateOutputRndSubmitNotification;
use App\Actions\KpiMonitoring\CreateOutputRndSubmitTask;
use App\Actions\KpiMonitoring\GetOutputRnDDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\OutputRnDFormRequest;
use App\Http\Requests\KpiMonitoring\OutputRnDSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\KpiMonitoring\OutputRndResource;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\RefOutputStatus;
use App\Models\RefOutputType;
use App\Models\User;
use App\Policies\OutputRnDPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OutputRnDController extends Controller
{

    protected $policy;
    protected $createOutputRndSubmitNotification;
    protected $createOutputRndSubmitTask;

    public function __construct(
        OutputRnDPolicy $policy,
        CreateOutputRndSubmitNotification $createOutputRndSubmitNotification,
        CreateOutputRndSubmitTask $createOutputRndSubmitTask
    ) {
        $this->policy = $policy;
        $this->createOutputRndSubmitNotification = $createOutputRndSubmitNotification;
        $this->createOutputRndSubmitTask = $createOutputRndSubmitTask;
    }

    public function index(GetOutputRnDDatatables $getoutput_rnd, OutputRnDSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $outputrnds = $getoutput_rnd->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/OutputRnd/Index', [
            "title" => "List Output R&D | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => OutputRndResource::collection($outputrnds),
                "filters" => $filters,
                "columns" => $getoutput_rnd->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.rnd-output.create"),
                "urlIndex" => route("panel.rnd-output.index"),
                "urlUploadBulk" => route("panel.rnd-output.bulk-create")
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
            ->where("category_id", OutputRnd::CATEGORY_ID)
            ->first();

        $outputrnd = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');


        return Inertia::render('KpiMonitoring/SubmitNewKpi/OutputRnd/Create', [
            "title" => "Create Output R&D | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => optional($outputrnd)->load("fileable"),
                "user" => $request->user(),
                "outputTypes" => RefOutputType::all(),
                "outputStatuses" => RefOutputStatus::all(),
                "urlStore" => route("panel.rnd-output.store"),
                "urlIndex" => route("panel.rnd-output.index")
            ]
        ]);
    }

    public function store(OutputRnDFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $outputrnd = OutputRnd::create([
                "user_id" => $userId = Auth::id(),
                "date_output" => $arrData["date_output"],
                "output" => $arrData["output"],
                "type" => $arrData["type"],
                "status" => $arrData["status"],
                "source_project" => $arrData["source_project"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "created_by" => $userId
            ]);

            $outputrnd->kpiAchievement()->create([
                "title" => $outputrnd->output,
                "user_id" => Auth::id(),
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $outputrnd->date_output,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $outputrnd,
                $requestFiles,
                $arrData,
                OutputRnd::FILEABLE_FILE_CODE
            );

            $this->createOutputRndSubmitNotification->execute($outputrnd);
            $this->createOutputRndSubmitTask->execute($outputrnd, $user);

            return $outputrnd;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.rnd-output.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Output R&D Success!"
            ]);
    }


    public function show(Request $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->view($request->user(), $outputrnd)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $outputrnd->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/OutputRnd/Show', [
            "title" => "View R&D Output | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $outputrnd->load("kpiAchievement.user", "output_type", "output_status", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $outputrnd),
                "urlEdit" => route("panel.rnd-output.edit", $outputrnd),
                "urlIndex" => route("panel.rnd-output.index")
            ]
        ]);
    }

    public function edit(Request $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->update($request->user(), $outputrnd)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/OutputRnd/Edit', [
            "title" => "Edit R&D Output | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $outputrnd->load("fileable"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $outputrnd),
                "user" => $outputrnd->kpiAchievement->user,

                "outputTypes" => RefOutputType::all(),
                "outputStatuses" => RefOutputStatus::all(),

                "urlUpdate" => route("panel.rnd-output.update", $outputrnd),
                "urlShow" => route("panel.rnd-output.show", $outputrnd),
                "urlIndex" => route("panel.rnd-output.index")
            ]
        ]);
    }

    public function update(OutputRnDFormRequest $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->update($request->user(), $outputrnd)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $outputrnd) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $outputrnd->update([
                // "user_id" => $userId = Auth::id(),
                "date_output" => $arrData["date_output"],
                "output" => $arrData["output"],
                "type" => $arrData["type"],
                "status" => $arrData["status"],
                "source_project" => $arrData["source_project"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "updated_id" => Auth::id()
            ]);

            $kpiAchievement = $outputrnd->kpiAchievement;
            $kpiAchievement->update([
                "title" => $outputrnd->output,
                "category_id" => OutputRnd::CATEGORY_ID,
                "date" => $outputrnd->date_output,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $outputrnd->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $outputrnd,
                $requestFiles,
                $arrData,
                OutputRnd::FILEABLE_FILE_CODE
            );

            $this->createOutputRndSubmitNotification->execute($outputrnd);
            $this->createOutputRndSubmitTask->execute($outputrnd, $user);

            return $outputrnd;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.rnd-output.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update R&D Output Success!"
            ]);
    }

    public function destroy(Request $request, OutputRnd $outputrnd)
    {
        if (!$this->policy->delete($request->user(), $outputrnd)) {
            abort(403);
        }

        DB::transaction(function () use ($outputrnd) {
            $outputrnd->kpiAchievement->delete();
            $outputrnd->update([
                "deleted_by" => Auth::id()
            ]);

            $outputrnd->taskable()->delete();
            $outputrnd->notifable()->delete();

            $outputrnd->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.rnd-output.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete R&D Output Success!"
            ]);
    }
}
