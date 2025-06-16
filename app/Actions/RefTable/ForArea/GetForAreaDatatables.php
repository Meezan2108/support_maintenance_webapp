<?php

namespace App\Actions\RefTable\ForArea;

use App\Helpers\DatatablesHelper;
use App\Models\RefForArea;
use App\Models\RefForGroup;

class GetForAreaDatatables
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "ref_for_area.code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "ref_for_area.description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Group",
            "name" => "ref_for_group.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Updated At",
            "name" => "ref_for_area.updated_at",
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
        $refForArea = new RefForArea();
        $refForGroup = new RefForGroup();

        $user = RefForArea::with("group")
            ->leftJoin(
                $refForGroup->getTable(),
                $refForArea->qualifyColumn("ref_for_group_id"),
                "=",
                $refForGroup->qualifyColumn("id")
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
                $filters["order_by"] ?? $refForArea->qualifyColumn('description'),
                $filters["order_type"] ?? 'asc'
            )
            ->select(
                $refForArea->qualifyColumn("id"),
                $refForArea->qualifyColumn("code"),
                $refForArea->qualifyColumn("description"),
                $refForArea->qualifyColumn("ref_for_group_id"),
                $refForArea->qualifyColumn("updated_at"),
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
