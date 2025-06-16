<?php

namespace App\Actions\RefTable\ForGroup;

use App\Helpers\DatatablesHelper;
use App\Models\RefForArea;
use App\Models\RefForCategory;
use App\Models\RefForGroup;

class GetForGroupDatatables
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "ref_for_group.code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "ref_for_group.description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Category",
            "name" => "ref_for_category.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Updated At",
            "name" => "ref_for_group.updated_at",
            "orderable" => true,
            "isDateTime" => true,
            "class" => "text-nowrap"
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
        $refForGroup = new RefForGroup();
        $refForCategory = new RefForCategory();

        $user = RefForGroup::with("category")
            ->leftJoin(
                $refForCategory->getTable(),
                $refForGroup->qualifyColumn("ref_for_category_id"),
                "=",
                $refForCategory->qualifyColumn("id")
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
            ->orderBy(
                $filters["order_by"] ?? $refForGroup->qualifyColumn('description'),
                $filters["order_type"] ?? 'asc'
            )
            ->select(
                $refForGroup->qualifyColumn("id"),
                $refForGroup->qualifyColumn("code"),
                $refForGroup->qualifyColumn("description"),
                $refForGroup->qualifyColumn("ref_for_category_id"),
                $refForGroup->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $user;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
