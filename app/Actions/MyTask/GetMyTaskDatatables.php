<?php

namespace App\Actions\MyTask;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\Taskable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetMyTaskDatatables
{
    protected $columns = [];

    public function __construct()
    {

        $userTable = new User();

        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Application ID",
                "name" => "code_id",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Title",
                "name" => "title",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Category",
                "name" => "category",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Status",
                "name" => "approval_status",
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
                "label" => "Reviewer / Submiter",
                "name" => $userTable->qualifyColumn("name"),
                "orderable" => true,
                "searchable" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "Updated At",
                "name" => "updated_at",
                "orderable" => true,
                "isDateTime" => true,
                "class" => "text-nowrap"
            ],
            [
                "label" => "",
                "name" => "link",
                "orderable" => false,
                "searchable" => false,
                "isHtml" => true,
                "class" => "text-nowrap",
            ],
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

        $userTable = new User();
        $taskable = new Taskable();
        $data = Taskable::with("reference", "submitedUser")
            ->leftJoin($userTable->getTable(), $userTable->qualifyColumn("id"), "=", $taskable->qualifyColumn("submited_user_id"))
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
            ->where(function ($query) use ($user, $taskable) {
                return $query->where(function ($query) use ($user, $taskable) {
                    return $query->where("code_type", "group")
                        ->when(
                            $user->hasRole("Division Director")
                                && !$user->hasRole(["LKM Director", "R&D Coordinator", "RMC"]),
                            function ($query) use ($user, $taskable) {
                                return $query->where(
                                    $taskable->qualifyColumn("ref_division_id"),
                                    optional($user->division)->id
                                );
                            }
                        )
                        ->whereIn("model_id", $user->roles->pluck("id")->toArray());
                })->orWhere(function ($query) use ($user) {
                    return $query->where("code_type", "user")
                        ->where("model_id", $user->id);
                });
            })
            ->orderBy(
                $filters["order_by"] ?? $taskable->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select("taskable.*", $userTable->qualifyColumn("name"))
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $data;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
