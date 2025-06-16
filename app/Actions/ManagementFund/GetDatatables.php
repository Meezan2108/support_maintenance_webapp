<?php

namespace App\Actions\ManagementFund;

use App\Actions\Role\GetRolesCanViewAll;
use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\Proposal;
use App\Models\ProposalProjectTeam;
use App\Models\ProposalResearcher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class GetDatatables
{
    protected $type = Proposal::TYPE_TRF;
    protected $columns = [];

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

        $user = User::find(Auth::id());

        $roleCanViewAll = (new GetRolesCanViewAll)->execute();

        return Proposal::with("researcher", "approvement")
            ->leftJoin(
                $researcher->getTable(),
                $proposal->qualifyColumn("id"),
                "=",
                $researcher->qualifyColumn("proposal_id")
            )
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) {
                        continue;
                    }

                    $search = isset($filters['search_values'][$key]) === true
                        ? $filters['search_values'][$key] : false;

                    $query->when(
                        isset($filters['search_values'][$key]) === true,
                        function ($query) use ($column, $search) {
                            $query->where($column, 'like', '%' . $search . '%');
                        }
                    );
                }
            })
            ->where(function ($query) use ($user, $researcher, $roleCanViewAll) {
                $roleDivisionDir = Role::find(User::ROLE_DIVISION_DIRECTOR);
                return $query->when($user->hasRole([$roleDivisionDir])
                    && !$user->hasAnyRole($roleCanViewAll), function ($query) use ($user, $researcher) {
                    return $query->where($researcher->qualifyColumn('ref_division_id'), $user->ref_division_id)
                        ->where("approval_status", "!=", Approvement::STATUS_DRAFT);
                })->when($user->hasAnyRole($roleCanViewAll), function ($query) {
                    return $query->where("approval_status", "!=", Approvement::STATUS_DRAFT);
                })->orWhere(function ($query) use ($user) {
                    $roleResearcher = Role::find(User::ROLE_RESEARCHER);
                    return $query->when($user->hasRole([$roleResearcher]), function ($query) use ($user) {
                        return $query->where(function ($query) use ($user) {
                            return $query->where("user_id", $user->id)
                                ->orWhereHas("teams", function ($query) {
                                    $projectTeam = new ProposalProjectTeam();
                                    return $query->where($projectTeam->qualifyColumn("user_id"), Auth::id());
                                });
                        })
                            ->where(function ($query) {
                                return $query->where('is_by_rmc', 0)
                                    ->orWhere(function ($query) {
                                        return $query->where('is_by_rmc', 1)
                                            ->where('approval_status', Approvement::STATUS_APPROVED);
                                    });
                            });
                    });
                });
            })
            ->where('proposal_type', $this->type)
            ->where('approval_status', '!=', Approvement::STATUS_TEMP)
            ->orderBy(
                $filters["order_by"] ?? $proposal->qualifyColumn('id'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $proposal->qualifyColumn("id"),
                $proposal->qualifyColumn('user_id'),
                $proposal->qualifyColumn("application_id"),
                $proposal->qualifyColumn("project_title"),
                $researcher->qualifyColumn("name"),
                $proposal->qualifyColumn("approval_status"),
                $proposal->qualifyColumn("keywords"),
                $proposal->qualifyColumn("updated_at"),
                $proposal->qualifyColumn("is_by_rmc"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function setColumn($columns = null)
    {
        $this->columns = $columns ?? [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Application ID",
                "name" => "proposal.application_id",
                "orderable" => true,
                "searchable" => true
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
                "searchable" => true
            ],
            [
                "label" => "Status",
                "name" => "proposal.approval_status",
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
                "label" => "Keyword",
                "name" => "proposal.keywords",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-250"
            ],
            [
                "label" => "Updated At",
                "name" => "proposal.updated_at",
                "orderable" => true,
                "isDateTime" => true,
                "class" => "text-nowrap"
            ]
        ];

        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setType($type = Proposal::TYPE_TRF)
    {
        $this->type = $type;
        return $this;
    }
}
