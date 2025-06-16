<?php

namespace App\Actions\ProjectMonitoring\ResearchProgressNoFund;

use App\Actions\Role\GetRolesCanViewAll;
use App\Helpers\DatatablesHelper;
use App\Models\ExtensionProject;
use App\Models\RefReportType;
use App\Models\ReportResearchProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class GetResearchProgressNoFundDatatables
{
    protected $columns = [];

    public function __construct()
    {

        $reportType = new RefReportType();
        $report = new ReportResearchProgress();

        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Project Title",
                "name" => $report->qualifyColumn("project_title"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Type Of Report",
                "name" => $reportType->qualifyColumn("description"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
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
                "name" => $report->qualifyColumn("approval_status"),
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap",
                "searchtype" => "select",
                "options" => collect(ExtensionProject::ARR_STATUS)->map(function ($item, $index) {
                    return [
                        "id" => $index,
                        "label" => $item
                    ];
                })
            ],
            [
                "label" => "Updated At",
                "name" => $report->qualifyColumn("updated_at"),
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
        $user = User::find(Auth::id());
        $reportType = new RefReportType();
        $report = new ReportResearchProgress();

        $roleCanViewAll = (new GetRolesCanViewAll)->execute();

        $data = ReportResearchProgress::with("approvementStep")
            ->join(
                $reportType->getTable(),
                $report->qualifyColumn("ref_report_type_id"),
                "=",
                $reportType->qualifyColumn("id")
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
            ->where(function ($query) use ($user, $report, $roleCanViewAll) {
                return $query->when($user->hasRole([Role::find(User::ROLE_DIVISION_DIRECTOR)])
                    && !$user->hasAnyRole($roleCanViewAll), function ($query) use ($user, $report) {
                    return $query->where($report->qualifyColumn('ref_division_id'), $user->ref_division_id);
                })->orWhere(function ($query) use ($user) {
                    return $query->when($user->hasRole([Role::find(User::ROLE_RESEARCHER)]), function ($query) use ($user) {
                        return $query->where("user_id", $user->id);
                    });
                });
            })
            ->where(
                $report->qualifyColumn('approval_status'),
                '!=',
                ReportResearchProgress::STATUS_DRAFT
            )
            ->whereNull($report->qualifyColumn('proposal_id'))
            ->orderBy(
                $filters["order_by"] ?? $report->qualifyColumn('id'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $report->qualifyColumn("id"),
                $report->qualifyColumn('project_title'),
                $reportType->qualifyColumn("description"),
                $report->qualifyColumn('user_id'),
                $report->qualifyColumn("approval_status"),
                $report->qualifyColumn("updated_at"),
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
