<?php

namespace App\Actions\KpiMonitoring;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\ResearcherInvolveable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetRecognitionDatatables
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
                "name" => "recognition.project",
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
                "name" => "recognition.recognition_date",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Attachment",
                "name" => "files",
                "class" => "text-nowrap",
                "orderable" => false,
                "searchable" => false,
                "isHtml" => true,
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
                "name" => "recognition.updated_at",
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
        $recognition = new Recognition();
        $kpiAchievement = new KpiAchievement();
        $user = User::find(Auth::id());

        $data = Recognition::with("kpiAchievement.user")
            ->join(
                $kpiAchievement->getTable(),
                function ($join) use ($recognition, $kpiAchievement) {
                    $join->on($recognition->qualifyColumn("id"), $kpiAchievement->qualifyColumn("reff_id"));
                    $join->where($kpiAchievement->qualifyColumn("reff_type"), Recognition::class);
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
                $filters["order_by"] ?? $recognition->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $recognition->qualifyColumn("id"),
                $recognition->qualifyColumn("user_id"),
                $recognition->qualifyColumn("date as recognition_date"),
                $recognition->qualifyColumn("project"),
                $user->qualifyColumn('name'),
                $kpiAchievement->qualifyColumn("approval_status"),
                $recognition->qualifyColumn("updated_at"),
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
