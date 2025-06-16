<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\ImportedGermplasm;
use App\Models\KpiAchievement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetImportedGermplasmDatatables
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
                "name" => "imported_germplasm.year",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Quarterly",
                "name" => "imported_germplasm.quarter",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Germplasm",
                "name" => "imported_germplasm.no_germplasm",
                "orderable" => true,
                "searchable" => true,
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
                "name" => "imported_germplasm.updated_at",
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
        $germplasm = new ImportedGermplasm();
        $kpiAchievement = new KpiAchievement();
        $user = User::find(Auth::id());

        return ImportedGermplasm::with("kpiAchievement.user")
            ->join(
                $kpiAchievement->getTable(),
                function ($join) use ($germplasm, $kpiAchievement) {
                    $join->on($germplasm->qualifyColumn("id"), $kpiAchievement->qualifyColumn("reff_id"));
                    $join->where($kpiAchievement->qualifyColumn("reff_type"), ImportedGermplasm::class);
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
                $filters["order_by"] ?? $germplasm->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $germplasm->qualifyColumn("id"),
                $germplasm->qualifyColumn("year"),
                $germplasm->qualifyColumn("quarter"),
                $germplasm->qualifyColumn("no_germplasm"),
                $user->qualifyColumn('name'),
                $germplasm->qualifyColumn('user_id'),
                $kpiAchievement->qualifyColumn("approval_status"),
                $germplasm->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
