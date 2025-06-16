<?php

namespace App\Actions\ApplicationManagement\TechnicalEvaluation;

use App\Actions\Role\GetRolesCanViewAll;
use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\ApprovementStep;
use App\Models\Proposal;
use App\Models\ProposalResearcher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GetEvaluationDatatables
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
                "label" => "Application ID",
                "name" => "proposal.application_id",
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Project Title",
                "name" => "proposal.project_title",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Name",
                "name" => "proposal_researcher.name",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Type",
                "name" => "proposal.proposal_type",
                "orderable" => true,
                "searchable" => false,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Status",
                "name" => "proposal.approval_status",
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap",
                "searchtype" => "select",
                "options" => collect(Proposal::ARR_STATUS)->map(function ($item, $index) {
                    return [
                        "id" => $index,
                        "label" => $item
                    ];
                })
            ],
            [
                "label" => "Division Director",
                "name" => "reviewer1.name",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "RMC",
                "name" => "reviewer2.name",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Coordinator",
                "name" => "reviewer3.name",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Updated At",
                "name" => "proposal.updated_at",
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
        $proposal = new Proposal();
        $researcher = new ProposalResearcher();
        $approvementStep = new ApprovementStep();
        $user = new User();

        $roleCanViewAll = (new GetRolesCanViewAll)->execute();

        $data = Proposal::with("researcher", "approvementStep")
            ->leftJoin(
                $researcher->getTable(),
                $proposal->qualifyColumn("id"),
                "=",
                $researcher->qualifyColumn("proposal_id")
            )
            ->leftJoin(
                $approvementStep->getTable(),
                function ($join) use ($proposal, $approvementStep) {
                    $join->on($proposal->qualifyColumn("id"), $approvementStep->qualifyColumn("approvementstepable_id"));
                    $join->where($approvementStep->qualifyColumn("approvementstepable_type"), "App\Models\Proposal");
                }
            )
            ->leftJoin(
                $user->getTable() . " as reviewer1",
                $approvementStep->qualifyColumn("reviewer_1"),
                "=",
                DB::raw("[reviewer1].[id]")
            )
            ->leftJoin(
                $user->getTable() . " as reviewer2",
                $approvementStep->qualifyColumn("reviewer_2"),
                "=",
                DB::raw("[reviewer2].[id]")
            )

            ->leftJoin(
                $user->getTable() . " as reviewer3",
                $approvementStep->qualifyColumn("reviewer_3"),
                "=",
                DB::raw("[reviewer3].[id]")
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
            ->when($user->hasRole([Role::find(User::ROLE_DIVISION_DIRECTOR)])
                && !$user->hasAnyRole($roleCanViewAll), function ($query) use ($user, $researcher) {
                return $query->where($researcher->qualifyColumn('ref_division_id'), $user->ref_division_id);
            })
            ->whereNotIn('approval_status', [Approvement::STATUS_DRAFT, Approvement::STATUS_TEMP])
            ->orderBy(
                $filters["order_by"] ?? $proposal->qualifyColumn('id'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $proposal->qualifyColumn("id"),
                $proposal->qualifyColumn('user_id'),
                $proposal->qualifyColumn("application_id"),
                $proposal->qualifyColumn("project_title"),
                $proposal->qualifyColumn("proposal_type"),
                $researcher->qualifyColumn("name"),
                $proposal->qualifyColumn("approval_status"),
                DB::raw("[reviewer1].[name] as name_rev1"),
                DB::raw("[reviewer2].[name] as name_rev2"),
                DB::raw("[reviewer3].[name] as name_rev3"),
                $proposal->qualifyColumn("updated_at"),
            )
            // ->toSql();
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $data;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
