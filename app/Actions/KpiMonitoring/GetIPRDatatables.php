<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\IPR;
use App\Models\KpiAchievement;
use App\Models\KpiMonitoring;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetIPRDatatables
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
                "name" => "ipr.output",
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
                "label" => "Date",
                "name" => "ipr.date",
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
                "name" => "ipr.updated_at",
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
        $ipr = new IPR();
        $kpiAchievement = new KpiAchievement();
        $user = User::find(Auth::id());

        $data = IPR::with("kpiAchievement.user")
            ->join(
                $kpiAchievement->getTable(),
                function ($join) use ($ipr, $kpiAchievement) {
                    $join->on($ipr->qualifyColumn("id"), $kpiAchievement->qualifyColumn("reff_id"));
                    $join->where($kpiAchievement->qualifyColumn("reff_type"), IPR::class);
                }
            )
            ->join(
                $user->getTable(),
                function ($join) use ($user, $kpiAchievement) {
                    $join->on($user->qualifyColumn("id"), $kpiAchievement->qualifyColumn("user_id"));
                }
            )
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) continue;

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
                $filters["order_by"] ?? $ipr->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $ipr->qualifyColumn("id"),
                $ipr->qualifyColumn("date"),
                $ipr->qualifyColumn("output"),
                $user->qualifyColumn('name'),
                $ipr->qualifyColumn("user_id"),
                $kpiAchievement->qualifyColumn("approval_status"),
                $ipr->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $data;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
