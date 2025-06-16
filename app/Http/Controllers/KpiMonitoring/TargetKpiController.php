<?php

namespace App\Http\Controllers\KpiMonitoring;

use App\Actions\KpiMonitoring\GetTargetKpiDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\KpiMonitoring\TargetKpiFormRequest;
use App\Http\Requests\KpiMonitoring\TargetKpiSearchRequest;
use App\Http\Resources\KpiMonitoring\TargetKpiResource;
use App\Models\Approvement;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use App\Policies\TargetKpiPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TargetKpiController extends Controller
{

    protected $policy;

    public function __construct(TargetKpiPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetTargetKpiDatatables $datatables, TargetKpiSearchRequest $request)
    {
        if (!$this->policy->viewAny($request->user())) {
            abort(403);
        }

        $filters = $request->validated();
        $list = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('KpiMonitoring/TargetKpi/Index', [
            "title" => "List Agency KPI Target | R&D LKM KPI Monitoring",
            "additional" => [
                "list" => TargetKpiResource::collection($list),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($request->user()),
                "urlCreate" => route("panel.target-kpi.create"),
                "urlIndex" => route("panel.target-kpi.index"),
                "urlDownload" => route("panel.target-kpi.download.index")
            ]
        ]);
    }


    public function create(Request $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        $targetKpi = TargetKpi::query()
            ->where("user_id", Auth::id())
            ->where("approval_status", Approvement::STATUS_DRAFT)
            ->first();

        $arrPeriod = RefTargetKpiPeriod::all();
        $arrCategory = RefTargetKpiCategory::query()
            ->whereNull("parent_id")
            ->get();

        $arrSubCategory = RefTargetKpiCategory::query()
            ->whereNotNull("parent_id")
            ->get();

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/TargetKpi/Create', [
            "title" => "Create Agency KPI Target | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $targetKpi,
                "user" => $request->user(),
                "arrPeriod" => $arrPeriod,
                "arrCategory" => $arrCategory,
                "arrSubCategory" => $arrSubCategory,
                "urlStore" => route("panel.target-kpi.store"),
                "urlIndex" => route("panel.target-kpi.index")
            ]
        ]);
    }

    public function store(TargetKpiFormRequest $request)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request) {

            $arrData = $request->validated();

            $isSubmit = $arrData["is_submited"] ?? false;

            $userId = Auth::id();

            // check is data exists;
            $selTarget = TargetKpi::query()
                ->where("year", $arrData["year"])
                ->where("period_id", $arrData["period_id"])
                ->where("category_id", $arrData["category_id"])
                ->when($arrData["sub_category_id"] ?? false, function ($query) use ($arrData) {
                    return $query->where("sub_category_id", $arrData["sub_category_id"]);
                })
                ->where("approval_status", "!=", Approvement::STATUS_REJECTED)
                ->get();

            $isExist = $selTarget->count() > 0 ? true : false;
            if ($isExist) {
                throw ValidationException::withMessages([
                    "year" => 'Target Exist! <a href="'
                        . route("panel.target-kpi.show", ["target" => $selTarget->first()->id])
                        . '">Click Here</a>'
                ]);
            }

            $targetKpi = TargetKpi::query()
                ->where("user_id", $userId)
                ->where("approval_status", Approvement::STATUS_DRAFT)
                ->first();

            $arrCreate = [
                "user_id" => $userId,
                "year" => $arrData["year"] ?? null,
                "period_id" => $arrData["period_id"] ?? null,
                "category_id" => $arrData["category_id"] ?? null,
                "sub_category_id" => $arrData["sub_category_id"] ?? null,
                "target" => $arrData["target"] ?? 0,
                "approval_status" => $isSubmit
                    ? Approvement::STATUS_APPROVED
                    : Approvement::STATUS_DRAFT,
                "created_by" => $userId
            ];
            if (!$targetKpi) {
                $targetKpi = TargetKpi::create($arrCreate);
            } else {
                $targetKpi->update($arrCreate);
            }

            return $targetKpi;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.target-kpi.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Agency KPI Target Success!"
            ]);
    }


    public function show(Request $request, TargetKpi $target)
    {
        if (!$this->policy->view($request->user(), $target)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/TargetKpi/Show', [
            "title" => "View Agency KPI Target | R&D LKM KPI Monitoring",
            "additional" => [
                "initValue" => $target->load("user", "category", "subCategory", "period"),
                "filters" => $filters,
                "canEdit" => $this->policy->update($request->user(), $target),
                "urlIndex" => route("panel.target-kpi.index")
            ]
        ]);
    }

    public function edit(Request $request, TargetKpi $target)
    {
        if (!$this->policy->update($request->user(), $target)) {
            abort(403);
        }

        $targetKpi = $target;

        $arrPeriod = RefTargetKpiPeriod::all();
        $arrCategory = RefTargetKpiCategory::query()
            ->whereNull("parent_id")
            ->get();

        $arrSubCategory = RefTargetKpiCategory::query()
            ->whereNotNull("parent_id")
            ->get();

        $filters = $request->session()->get('filters');

        return Inertia::render('KpiMonitoring/TargetKpi/Edit', [
            "title" => "Create Agency KPI Target | R&D LKM KPI Monitoring",
            "additional" => [
                "filters" => $filters,
                "initValue" => $targetKpi,
                "user" => $request->user(),
                "arrPeriod" => $arrPeriod,
                "arrCategory" => $arrCategory,
                "arrSubCategory" => $arrSubCategory,
                "urlUpdate" => route("panel.target-kpi.update", ["target" => $target->id]),
                "urlIndex" => route("panel.target-kpi.index")
            ]
        ]);
    }

    public function update(TargetKpiFormRequest $request, TargetKpi $target)
    {
        if (!$this->policy->create($request->user())) {
            abort(403);
        }

        DB::transaction(function () use ($request, $target) {

            $arrData = $request->validated();

            $userId = Auth::id();

            // check is data exists;
            $selTarget = TargetKpi::query()
                ->where("year", $arrData["year"])
                ->where("period_id", $arrData["period_id"])
                ->where("category_id", $arrData["category_id"])
                ->when($arrData["sub_category_id"] ?? false, function ($query) use ($arrData) {
                    return $query->where("sub_category_id", $arrData["sub_category_id"]);
                })
                ->where("approval_status", "!=", Approvement::STATUS_REJECTED)
                ->where("id", "!=", $target->id)
                ->get();

            $isExist = $selTarget->count() > 0 ? true : false;
            if ($isExist) {
                throw ValidationException::withMessages([
                    "year" => 'Target Exist! <a href="'
                        . route("panel.target-kpi.show", ["target" => $selTarget->first()->id])
                        . '">Click Here</a>'
                ]);
            }

            $targetKpi = $target;

            $targetKpi->update([
                "user_id" => $userId,
                "year" => $arrData["year"] ?? null,
                "period_id" => $arrData["period_id"] ?? null,
                "category_id" => $arrData["category_id"] ?? null,
                "sub_category_id" => $arrData["sub_category_id"] ?? null,
                "target" => $arrData["target"] ?? 0,
            ]);

            return $targetKpi;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.target-kpi.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Agency KPI Target Success!"
            ]);
    }

    public function destroy(Request $request, TargetKpi $target)
    {
        if (!$this->policy->delete($request->user(), $target)) {
            abort(403);
        }

        DB::transaction(function () use ($target) {
            $target->update([
                "deleted_by" => Auth::id()
            ]);
            $target->delete();
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.target-kpi.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Agency KPI Target Success!"
            ]);
    }
}
