<?php

namespace App\Actions\RefTable\Patent;

use App\Helpers\DatatablesHelper;
use App\Models\RefPatent;

class GetPatentDatatables
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
            "label" => "Detail",
            "name" => "detail",
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
        $user = RefPatent::when($filters['search_fields'] ?? false, function ($query) use ($filters) {

            $allowedFields = DatatablesHelper::getSearchableField($this->columns);
            foreach ($filters['search_fields'] as $key => $column) {
                if (!in_array($column, $allowedFields)) continue;

                $query->when($filters['search_values'][$key] ?? false, function ($query, $search) use ($column) {
                    $query->where($column, 'like', '%' . $search . '%');
                });
            }
        })
            ->orderBy($filters["order_by"] ?? 'description', $filters["order_type"] ?? 'asc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $user;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
