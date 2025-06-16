<?php

namespace App\Http\Controllers\KpiMonitoring\SubmitNewKpi;

use App\Actions\KpiMonitoring\CreateFile;
use App\Actions\KpiMonitoring\CreateIPRSubmitNotification;
use App\Actions\KpiMonitoring\CreateIPRSubmitTask;
use App\Actions\KpiMonitoring\GetIPRDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\IPRFormRequest;
use App\Http\Requests\KpiMonitoring\IPRSearchRequest;
use App\Http\Resources\KpiMonitoring\IPRResource;
use App\Models\Approvement;
use App\Models\IPR;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\RefPatent;
use App\Models\User;
use App\Policies\IPRPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IPRController extends Controller
{

    protected $policy;
    protected $createIPRSubmitNotification;
    protected $createIPRSubmitTask;

    public function __construct(
        IPRPolicy $policy,
        CreateIPRSubmitNotification $createIPRSubmitNotification,
        CreateIPRSubmitTask $createIPRSubmitTask
    ) {
        $this->policy = $policy;
        $this->createIPRSubmitNotification = $createIPRSubmitNotification;
        $this->createIPRSubmitTask = $createIPRSubmitTask;
    }

    public function index(GetIPRDatatables $getipr, IPRSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $ipr = $getipr->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/SubmitNewKpi/IPR/Index', [
            "title" => "List Intellectual Property Right | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => IPRResource::collection($ipr),
                "filters" => $filters,
                "columns" => $getipr->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "canCreateBulk" => $this->policy->createBulk($request->user()),

                "urlCreate" => route("panel.ipr.create"),
                "urlIndex" => route("panel.ipr.index"),
                "urlUploadBulk" => route("panel.ipr.bulk-create")
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
            ->where("category_id", IPR::CATEGORY_ID)
            ->first();

        $ipr = optional($kpiAchievement)->reff;
        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/IPR/Create', [
            "title" => "Create Intellectual Property Right | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => optional($ipr)->load("fileable"),
                "user" => $request->user(),
                "patentTypes" => RefPatent::all(),
                "urlStore" => route("panel.ipr.store"),
                "urlIndex" => route("panel.ipr.index")
            ]
        ]);
    }

    public function store(IPRFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $ipr = IPR::create([
                "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "output" => $arrData["output"],
                "ref_patent_id" => $arrData["ref_patent_id"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "created_by" => $userId
            ]);

            $ipr->kpiAchievement()->create([
                "title" => $ipr->output,
                "user_id" => Auth::id(),
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_SUBMITED
                    : Approvement::STATUS_DRAFT
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $ipr,
                $requestFiles,
                $arrData,
                IPR::FILEABLE_FILE_CODE
            );

            $this->createIPRSubmitNotification->execute($ipr);
            $this->createIPRSubmitTask->execute($ipr, $user);

            return $ipr;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ipr.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create IPR Success!"
            ]);
    }


    public function show(Request $request, IPR $ipr)
    {
        if (!$this->policy->view($request->user(), $ipr)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        $file = $ipr->fileable->each(fn($file) => $file->file_url = route('resources.fileable.show', [
            "fileable" => $file->id,
            "access_key" => $file->access_key
        ]));

        return Inertia::render('KpiMonitoring/SubmitNewKpi/IPR/Show', [
            "title" => "View Intellectual Property Right | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $ipr->load("kpiAchievement.user", "patent", "proposal"),
                "file" => $file,
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $ipr),
                "urlEdit" => route("panel.ipr.edit", $ipr),
                "urlIndex" => route("panel.ipr.index")
            ]
        ]);
    }

    public function edit(Request $request, IPR $ipr)
    {
        if (!$this->policy->update($request->user(), $ipr)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/SubmitNewKpi/IPR/Edit', [
            "title" => "Edit Intellectual Property Right | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $ipr->load("fileable"),
                "filters" => $filters,
                "canView" => $this->policy->view($request->user(), $ipr),
                "user" => $ipr->kpiAchievement->user,

                "patentTypes" => RefPatent::all(),

                "urlUpdate" => route("panel.ipr.update", $ipr),
                "urlShow" => route("panel.ipr.show", $ipr),
                "urlIndex" => route("panel.ipr.index")
            ]
        ]);
    }

    public function update(IPRFormRequest $request, IPR $ipr)
    {
        if (!$this->policy->update($request->user(), $ipr)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $ipr) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $user = User::find(Auth::id());

            $ipr->update([
                // "user_id" => $userId = Auth::id(),
                "date" => $arrData["date"],
                "output" => $arrData["output"],
                "ref_patent_id" => $arrData["ref_patent_id"],
                "proposal_id" => $arrData["proposal_id"] ?? null,
                "updated_id" => Auth::id()
            ]);

            $kpiAchievement = $ipr->kpiAchievement;
            $kpiAchievement->update([
                "title" => $ipr->output,
                "category_id" => IPR::CATEGORY_ID,
                "date" => $ipr->date,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_RE_SUBMIT
                    : $ipr->kpiAchievement->approval_status
            ]);

            $requestFiles = $request->hasFile('new_files')
                ? $request->file('new_files')
                : [];

            (new CreateFile)->execute(
                $ipr,
                $requestFiles,
                $arrData,
                OutputRnd::FILEABLE_FILE_CODE
            );

            $this->createIPRSubmitNotification->execute($ipr);
            $this->createIPRSubmitTask->execute($ipr, $user);

            return $ipr;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ipr.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update IPR Success!"
            ]);
    }

    public function destroy(Request $request, IPR $ipr)
    {
        if (!$this->policy->delete($request->user(), $ipr)) {
            abort(403);
        }

        DB::transaction(function () use ($ipr) {
            $ipr->kpiAchievement->delete();
            $ipr->update([
                "deleted_by" => Auth::id()
            ]);

            $ipr->taskable()->delete();
            $ipr->notifable()->delete();

            $ipr->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.ipr.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete IPR Success!"
            ]);
    }
}
