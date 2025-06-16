<?php

namespace App\Actions\User;

use App\Helpers\DatatablesHelper;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class GetUsersDatatables
{
    protected $columns;

    public function __construct()
    {
        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Name",
                "name" => "name",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Email",
                "name" => "email",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Roles",
                "name" => "roles",
                "searchable" => true,
                "searchtype" => "select",
                "options" => Role::all()->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "label" => $item->name
                    ];
                })
            ],
            [
                "label" => "Status",
                "name" => "status",
                "orderable" => true,
                "searchable" => true,
                "searchtype" => "select",
                "options" => [
                    [
                        "id" => 1,
                        "label" => "Active"
                    ],
                    [
                        "id" => 0,
                        "label" => "Non Active"
                    ]
                ]
            ],
            [
                "label" => "Register At",
                "name" => "created_at",
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
        $user = User::with(["roles"])
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) continue;

                    $search = $filters['search_values'][$key] ?? '';
                    $query->when($search !== '', function ($query) use ($column, $search) {
                        if ($column == 'roles') {
                            $query->whereHas('roles', function ($query) use ($search) {
                                $query->where('roles.id', $search);
                            });
                        } else if ($column == 'status') {
                            $query->where($column, $search);
                        } else {
                            $query->where($column, 'like', '%' . $search . '%');
                        }
                    });
                }
            })
            ->orderBy($filters["order_by"] ?? 'created_at', $filters["order_type"] ?? 'desc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $user;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
