<?php

namespace App\Actions\RefTable\ProjectStatus;

use App\Helpers\DatatablesHelper;
use App\Models\RefDivision;
use App\Models\RefStatusProject;

class GetProjectStatusDatatables
{

    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Updated At",
            "name" => "updated_at",
            "orderable" => true,
            "isDateTime" => true,
        ]
    ];

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(array $filters)
    {
        $user = RefStatusProject::when($filters['search_fields'] ?? false, function ($query) use ($filters) {

            $allowedFields = DatatablesHelper::getSearchableField($this->columns);
            foreach ($filters['search_fields'] as $key => $column) {
                if (!in_array($column, $allowedFields)) continue;

                $query->when($filters['search_values'][$key] ?? false, function ($query, $search) use ($column) {
                    $query->where($column, 'like', '%' . $search . '%');
                });
            }
        })
            ->orderBy($filters["order_by"] ?? 'code', $filters["order_type"] ?? 'asc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $user;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
