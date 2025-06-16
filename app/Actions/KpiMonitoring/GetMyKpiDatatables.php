<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\RefTargetKpiCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetMyKpiDatatables
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
                "label" => "Title",
                "name" => "kpi_achievement.title",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Project Leader",
                "name" => "users.name",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Category",
                "name" => "kpi_achievement.category_id",
                "orderable" => true,
                "searchable" => true,
                "searchtype" => "select",
                "options" => RefTargetKpiCategory::query()
                    ->whereNull('parent_id')->get()
                    ->map(function ($item, $index) {
                        return [
                            "id" => $item->id,
                            "label" => $item->description
                        ];
                    })
            ],
            [
                "label" => "Date",
                "name" => "kpi_achievement.date",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Status",
                "name" => "kpi_achievement.approval_status",
                "orderable" => true,
                "searchable" => false,
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
                "name" => "kpi_achievement.updated_at",
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
        $kpiAchievement = new KpiAchievement();
        $kpiCategory = new RefTargetKpiCategory();

        $user = User::find(Auth::id());

        return KpiAchievement::with("reff", "researcher")
            ->join(
                $user->getTable(),
                function ($join) use ($user, $kpiAchievement) {
                    $join->on($user->qualifyColumn("id"), $kpiAchievement->qualifyColumn("user_id"));
                }
            )
            ->join(
                $kpiCategory->getTable(),
                function ($join) use ($kpiCategory, $kpiAchievement) {
                    $join->on($kpiCategory->qualifyColumn("id"), $kpiAchievement->qualifyColumn("category_id"));
                }
            )
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                $selectFields = DatatablesHelper::getSelectableField($this->columns);

                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) continue;

                    $query->when(
                        $filters['search_values'][$key] ?? false,
                        function ($query, $search) use ($column, $selectFields) {

                            if (in_array($column, $selectFields)) {
                                return $query->where($column, $search);
                            }
                            return $query->where($column, 'like', '%' . $search . '%');
                        }
                    );
                }
            })
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($kpiAchievement, $user) {
                return $query->where(function ($query) use ($kpiAchievement, $user) {
                    return $query->where($kpiAchievement->qualifyColumn("user_id"), Auth::id())
                        ->orWhereHas("researcher", function ($query) use ($user) {
                            return $query->where($user->qualifyColumn("id"), Auth::id());
                        });
                });
            })
            ->where($kpiAchievement->qualifyColumn("approval_status"), '=', Approvement::STATUS_APPROVED)
            ->orderBy(
                $filters["order_by"] ?? $kpiAchievement->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $kpiAchievement->qualifyColumn("id"),
                $kpiAchievement->qualifyColumn("date"),
                $kpiAchievement->qualifyColumn("title"),
                $kpiAchievement->qualifyColumn("category_id"),
                $kpiCategory->qualifyColumn("description"),
                $kpiAchievement->qualifyColumn("user_id"),
                $user->qualifyColumn('name'),
                $kpiAchievement->qualifyColumn("approval_status"),
                $kpiAchievement->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
