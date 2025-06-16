<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\RefTargetKpiCategory;
use App\Models\TargetKpi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetTargetKpiByResearcherDatatables
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
                "label" => "Researcher Name",
                "name" => "users.name",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Year",
                "name" => "target_kpi.year",
                "orderable" => true,
                "searchable" => true
            ],
        ]);
    }

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(array $filters)
    {

        $targetKpi = new TargetKpi();
        $category = new RefTargetKpiCategory();

        $user = User::find(Auth::id());

        $data = TargetKpi::with("category", "subCategory", "period")
            ->join(
                $user->getTable(),
                function ($join) use ($user, $targetKpi) {
                    $join->on($user->qualifyColumn("id"), $targetKpi->qualifyColumn("user_id"));
                }
            )
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) continue;

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
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($targetKpi) {
                return $query->where($targetKpi->qualifyColumn("user_id"), Auth::id());
            })
            ->where($targetKpi->qualifyColumn("approval_status"), Approvement::STATUS_APPROVED)
            ->groupBy(
                $targetKpi->qualifyColumn("user_id"),
                $user->qualifyColumn("name"),
                $targetKpi->qualifyColumn("year")
            )
            ->orderBy(
                $filters["order_by"] ?? $targetKpi->qualifyColumn('year'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $targetKpi->qualifyColumn("user_id"),
                $user->qualifyColumn('name'),
                $targetKpi->qualifyColumn("year")
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $data;
    }

    public function getColumns()
    {
        return $this->columns->toArray();
    }
}
