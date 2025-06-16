<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\RefTargetKpiCategory;
use App\Models\RefTargetKpiPeriod;
use App\Models\TargetKpi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetTargetKpiDatatables
{
    protected $columns = [];

    public function __construct()
    {
        $this->columns = collect([
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Year",
                "name" => "target_kpi.year",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Period",
                "name" => "target_kpi.period_id",
                "orderable" => true,
                "searchable" => true,
                "searchtype" => "select",
                "options" => RefTargetKpiPeriod::query()
                    ->get()
                    ->map(function ($item) {
                        return [
                            "id" => $item->id,
                            "label" => $item->description
                        ];
                    })
            ],
            [
                "label" => "Category",
                "name" => "target_kpi.category_id",
                "orderable" => true,
                "searchable" => true,
                "searchtype" => "select",
                "options" => RefTargetKpiCategory::query()
                    ->whereNull("parent_id")
                    ->get()
                    ->map(function ($item) {
                        return [
                            "id" => $item->id,
                            "label" => $item->description
                        ];
                    })
            ],
            [
                "label" => "Target",
                "name" => "target_kpi.target",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Updated At",
                "name" => "target_kpi.updated_at",
                "orderable" => true,
                "isDateTime" => true,
                "class" => "text-nowrap"
            ]
        ]);
    }
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return
     */
    public function execute(array $filters)
    {
        $targetKpi = new TargetKpi();

        $user = User::find(Auth::id());

        return TargetKpi::with("category", "subCategory", "period")
            ->join(
                $user->getTable(),
                function ($join) use ($user, $targetKpi) {
                    $join->on($user->qualifyColumn("id"), $targetKpi->qualifyColumn("user_id"));
                }
            )
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) {
                        continue;
                    }

                    $query->when(
                        $filters['search_values'][$key] ?? false,
                        function ($query, $search) use ($column) {
                            $selColumn = $this->columns->firstWhere("name", "=", $column);

                            if (isset($selColumn["searchtype"]) ? $selColumn["searchtype"] == "select" : false) {
                                $query->where($column, $search);
                            } else {
                                $query->where($column, 'like', '%' . $search . '%');
                            }
                        }
                    );
                }
            })
            ->where("type", TargetKpi::TYPE_USER)
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($targetKpi) {
                return $query->where($targetKpi->qualifyColumn("user_id"), Auth::id());
            })
            ->orderBy(
                $filters["order_by"] ?? $targetKpi->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $targetKpi->qualifyColumn("id"),
                $targetKpi->qualifyColumn("year"),
                $targetKpi->qualifyColumn("period_id"),
                $targetKpi->qualifyColumn("category_id"),
                $targetKpi->qualifyColumn("sub_category_id"),
                $targetKpi->qualifyColumn("user_id"),
                $targetKpi->qualifyColumn("target"),
                $user->qualifyColumn('name'),
                $targetKpi->qualifyColumn("approval_status"),
                $targetKpi->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns->toArray();
    }
}
