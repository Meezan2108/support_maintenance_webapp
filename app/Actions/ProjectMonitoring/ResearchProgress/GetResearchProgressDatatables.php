<?php

namespace App\Actions\ProjectMonitoring\ResearchProgress;

use App\Actions\Role\GetRolesCanViewAll;
use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\ExtensionProject;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\ReportResearchProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class GetResearchProgressDatatables
{
    protected $columns = [];

    public function __construct()
    {
        $proposal = new Proposal();
        $researcher = new ProposalResearcher();
        $report = new ReportResearchProgress();

        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Project Number",
                "name" => $proposal->qualifyColumn("project_number"),
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Project Title",
                "name" => $proposal->qualifyColumn("project_title"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Project Leader",
                "name" => $researcher->qualifyColumn("name"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Keywords",
                "name" => $proposal->qualifyColumn("keywords"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-250"
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
        $proposal = new Proposal();
        $researcher = new ProposalResearcher();
        $report = new ReportResearchProgress();

        $roleCanViewAll = (new GetRolesCanViewAll)->execute();

        $data = ReportResearchProgress::with("proposal", "approvementStep")
            ->leftJoin(
                $proposal->getTable(),
                $report->qualifyColumn("proposal_id"),
                "=",
                $proposal->qualifyColumn("id")
            )
            ->leftJoin(
                $researcher->getTable(),
                $proposal->qualifyColumn("id"),
                "=",
                $researcher->qualifyColumn("proposal_id")
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
            ->where(function ($query) use ($user, $researcher, $proposal, $roleCanViewAll) {
                return $query->when($user->hasRole([Role::find(User::ROLE_DIVISION_DIRECTOR)])
                    && !$user->hasAnyRole($roleCanViewAll), function ($query) use ($user, $researcher) {
                    return $query->where($researcher->qualifyColumn('ref_division_id'), $user->ref_division_id);
                })->orWhere(function ($query) use ($user, $proposal) {
                    return $query->when($user->hasRole([Role::find(User::ROLE_RESEARCHER)]), function ($query) use ($user, $proposal) {
                        return $query->where($proposal->qualifyColumn('user_id'), $user->id);
                    });
                });
            })
            ->where(
                $report->qualifyColumn('approval_status'),
                '!=',
                ReportResearchProgress::STATUS_DRAFT
            )
            ->whereNotNull($report->qualifyColumn("proposal_id"))
            ->orderBy(
                $filters["order_by"] ?? $report->qualifyColumn('id'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $report->qualifyColumn("id"),
                $report->qualifyColumn('proposal_id'),
                $proposal->qualifyColumn('user_id'),
                $proposal->qualifyColumn("project_number"),
                $proposal->qualifyColumn("project_title"),
                $proposal->qualifyColumn("proposal_type"),
                $researcher->qualifyColumn("name"),
                $report->qualifyColumn("approval_status"),
                $proposal->qualifyColumn("keywords"),
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
