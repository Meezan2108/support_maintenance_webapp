<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\AnalyticalServiceLab;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetAnalyticalServiceLabDatatables
{
    protected $columns = [];

    public function __construct()
    {
        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Year",
                "name" => "analytical_service_lab.year",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Quarterly",
                "name" => "analytical_service_lab.quarter",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Sample",
                "name" => "analytical_service_lab.no_sample",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Analysis",
                "name" => "analytical_service_lab.no_analysis",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Analysis Protocol",
                "name" => "analytical_service_lab.no_analysis_protocol",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Project Leader",
                "name" => "users.name",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Status",
                "name" => "kpi_achievement.approval_status",
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap",
                "searchtype" => "select",
                "options" => collect(Approvement::ARR_STATUS)->map(function ($item, $index) {
                    return [
                        "id" => $index,
                        "label" => $item
                    ];
                })
            ],
            [
                "label" => "Updated At",
                "name" => "analytical_service_lab.updated_at",
                "orderable" => true,
                "isDateTime" => true,
                "class" => "text-nowrap"
            ]
        ];
    }

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(array $filters)
    {
        $asl = new AnalyticalServiceLab();
        $kpiAchievement = new KpiAchievement();
        $user = User::find(Auth::id());

        return AnalyticalServiceLab::with("kpiAchievement.user")
            ->join(
                $kpiAchievement->getTable(),
                function ($join) use ($asl, $kpiAchievement) {
                    $join->on($asl->qualifyColumn("id"), $kpiAchievement->qualifyColumn("reff_id"));
                    $join->where($kpiAchievement->qualifyColumn("reff_type"), AnalyticalServiceLab::class);
                }
            )
            ->leftJoin(
                $user->getTable(),
                function ($join) use ($user, $kpiAchievement) {
                    $join->on($user->qualifyColumn("id"), $kpiAchievement->qualifyColumn("user_id"));
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
                            $query->where($column, 'like', '%' . $search . '%');
                        }
                    );
                }
            })
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($kpiAchievement) {
                return $query->where($kpiAchievement->qualifyColumn("user_id"), Auth::id());
            })
            ->where($kpiAchievement->qualifyColumn("approval_status"), '!=', Approvement::STATUS_DRAFT)
            ->orderBy(
                $filters["order_by"] ?? $asl->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $asl->qualifyColumn("id"),
                $asl->qualifyColumn("year"),
                $asl->qualifyColumn("quarter"),
                $asl->qualifyColumn("no_sample"),
                $asl->qualifyColumn("no_analysis"),
                $asl->qualifyColumn("no_analysis_protocol"),
                $user->qualifyColumn('name'),
                $asl->qualifyColumn('user_id'),
                $kpiAchievement->qualifyColumn("approval_status"),
                $asl->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
