<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\KpiMonitoring;
use App\Models\Publication;
use App\Models\ResearcherInvolveable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetPublicationsDatatables
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
                "name" => "publication.title",
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
                "name" => "publication.date_published",
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
                "name" => "publication.updated_at",
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
        $publication = new Publication();
        $kpiAchievement = new KpiAchievement();
        $user = User::find(Auth::id());

        return Publication::with("kpiAchievement.user")
            ->join(
                $kpiAchievement->getTable(),
                function ($join) use ($publication, $kpiAchievement) {
                    $join->on($publication->qualifyColumn("id"), $kpiAchievement->qualifyColumn("reff_id"));
                    $join->where($kpiAchievement->qualifyColumn("reff_type"), Publication::class);
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
                return $query->where(function ($query) use ($kpiAchievement) {
                    return $query->where($kpiAchievement->qualifyColumn("user_id"), Auth::id())
                        ->orWhereHas("researcherInvolved", function ($query) {
                            $researcherInvolved = new ResearcherInvolveable();
                            return $query->where($researcherInvolved->qualifyColumn("user_id"), Auth::id());
                        });
                });
            })
            ->where($kpiAchievement->qualifyColumn("approval_status"), '!=', Approvement::STATUS_DRAFT)
            ->orderBy(
                $filters["order_by"] ?? $publication->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $publication->qualifyColumn("id"),
                $publication->qualifyColumn("user_id"),
                $publication->qualifyColumn("date_published"),
                $publication->qualifyColumn("title"),
                $user->qualifyColumn('name'),
                $kpiAchievement->qualifyColumn("approval_status"),
                $publication->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
