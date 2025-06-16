<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateRecognitionSubmitNotification;
use App\Actions\KpiMonitoring\CreateRecognitionSubmitTask;
use App\Actions\KpiMonitoring\CreateResearcherInvolved;
use App\Actions\KpiMonitoring\GetRecognitionDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\RecognitionFormRequest;
use App\Http\Requests\KpiMonitoring\RecognitionSearchRequest;
use App\Http\Resources\FileableResource;
use App\Http\Resources\KpiMonitoring\RecognitionResource;
use App\Models\Approvement;
use App\Models\Fileable;
use App\Models\KpiAchievement;
use App\Models\Recognition;
use App\Models\User;
use App\Policies\RecognitionPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class RecognitionController extends Controller
{

    protected $policy;
    protected $createRecognitionSubmitNotification;
    protected $createRecognitionSubmitTask;

    public function __construct(
        RecognitionPolicy $policy,
        CreateRecognitionSubmitNotification $createRecognitionSubmitNotification,
        CreateRecognitionSubmitTask $createRecognitionSubmitTask
    ) {
        $this->policy = $policy;
        $this->createRecognitionSubmitNotification = $createRecognitionSubmitNotification;
        $this->createRecognitionSubmitTask = $createRecognitionSubmitTask;
    }

    public function index(GetRecognitionDatatables $getrecognition, RecognitionSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $recognitions = $getrecognition->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Recognition/Index', [
            "title" => "List Recognition | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => RecognitionResource::collection($recognitions),
                "filters" => $filters,
                "columns" => $getrecognition->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.recognition.create"),
                "urlIndex" => route("panel.recognition.index"),
                "urlUploadBulk" => route("panel.recognition.bulk-create"),
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
            ->where("category_id", Recognition::CATEGORY_ID)
            ->first();

        $recognition = optional($kpiAchievement)->reff;

        $recognition_arr_type = collect(Recognition::ARR_TYPE)->map(function ($item, $index) {
            return [
                "id" => $index,
                "description" => $item
            ];
        });

        $recognitions = new Recognition;

        $recognition['old_files'] = FileableResource::collection($recognitions->fileable->sortByDesc("id"));

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Recognition/Create', [
            "title" => "Recognition | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $recognition,
                "user" => $request->user(),
                "recognitionTypes" => $recognition_arr_type,
                "urlStore" => route("panel.recognition.store"),
                "urlIndex" => route("panel.recognition.index"),
            ]
        ]);
    }

    public function store(RecognitionFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $recognition = Recognition::create([
                "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "recognition" => $arrData["recognition"],
                "type" => $arrData["type"],
                "project" => $arrData["project"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "created_by" => $userId
            ]);

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $recognition->fileable()->create(array_merge([
                        "code_type" => Recognition::FILEABLE_FILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $kpiAchievement = $recognition->kpiAchievement()->create([
                "title" => $recognition->recognition,
                "user_id" => Auth::id(),
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            (new CreateResearcherInvolved)->execute($recognition, $kpiAchievement, $arrData["researchers"] ?? []);

            $this->createRecognitionSubmitNotification->execute($recognition);
            $this->createRecognitionSubmitTask->execute($recognition, $user);

            return $recognition;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.recognition.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Recognition Success!"
            ]);
    }


    public function show(Request $request, Recognition $recognition)
    {
        if (!$this->policy->view($request->user(), $recognition)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $recognition["recognition_type"] = $recognition::ARR_TYPE[$recognition->type];

        $file = $recognition->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Recognition/Show', [
            "title" => "View Recognition | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $recognition->load("kpiAchievement.user", "proposal", "fileable", "researcherInvolved"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $recognition),
                "urlEdit" => route("panel.recognition.edit", $recognition),
                "urlIndex" => route("panel.recognition.index")
            ]
        ]);
    }

    public function edit(Request $request, Recognition $recognition)
    {
        if (!$this->policy->update($request->user(), $recognition)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/Recognition/Edit', [
            "title" => "Edit Recognition | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $recognition->load("fileable", "researcherInvolved"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $recognition),
                "user" => $recognition->kpiAchievement->user,

                "recognitionTypes" => Recognition::ARR_TYPE,

                "urlUpdate" => route("panel.recognition.update", $recognition),
                "urlShow" => route("panel.recognition.show", $recognition),
                "urlIndex" => route("panel.recognition.index")
            ]
        ]);
    }

    public function update(RecognitionFormRequest $request, Recognition $recognition)
    {
        if (!$this->policy->update($request->user(), $recognition)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $recognition) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $recognition->update([
                // "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "recoginition" => $arrData["recognition"],
                "project" => $arrData["project"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "type" => $arrData["type"],
                "updated_id" => Auth::id()
            ]);

            $arrIdNew = [];
            if ($request->hasFile('new_files')) {
                $requestFiles = $request->file('new_files');
                foreach ($requestFiles as $file) {
                    $fileableFormat = Fileable::prepareForDB($file);

                    $fileable = $recognition->fileable()->create(array_merge([
                        "code_type" => Recognition::FILEABLE_FILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));

                    $arrIdNew[] = $fileable->id;
                }
            }

            $arrIdOld = isset($arrData["old_files"])
                ? collect($arrData["old_files"])->pluck('id')->toArray()
                : [];

            $recognition->fileable()
                ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
                ->delete();

            $kpiAchievement = $recognition->kpiAchievement;
            $kpiAchievement->update([
                "title" => $recognition->recognition,
                "category_id" => Recognition::CATEGORY_ID,
                "date" => $recognition->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $recognition->kpiAchievement->approval_status
            ]);

            (new CreateResearcherInvolved)->execute($recognition, $kpiAchievement, $arrData["researchers"] ?? []);

            $this->createRecognitionSubmitNotification->execute($recognition);
            $this->createRecognitionSubmitTask->execute($recognition, $user);

            return $recognition;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.recognition.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Recognition Success!"
            ]);
    }

    public function destroy(Request $request, Recognition $recognition)
    {
        if (!$this->policy->delete($request->user(), $recognition)) {
            abort(403);
        }

        DB::transaction(function () use ($recognition) {
            $recognition->kpiAchievement->delete();
            $recognition->update([
                "deleted_by" => Auth::id()
            ]);


            $recognition->taskable()->delete();
            $recognition->notifable()->delete();

            $recognition->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.recognition.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Recognition Success!"
            ]);
    }
}
