<?php

namespace App\Actions\RefTable\SeoArea;

use App\Helpers\DatatablesHelper;
use App\Models\RefSeoArea;
use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;

class GetSeoAreaDatatables
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "ref_seo_area.code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "ref_seo_area.description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Group",
            "name" => "ref_seo_group.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Updated At",
            "name" => "ref_seo_area.updated_at",
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
        $refSeoArea = new RefSeoArea();
        $refSeoGroup = new RefSeoGroup();

        $user = RefSeoArea::with("group")
            ->leftJoin(
                $refSeoGroup->getTable(),
                $refSeoArea->qualifyColumn("ref_seo_group_id"),
                "=",
                $refSeoGroup->qualifyColumn("id")
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
                $filters["order_by"] ?? $refSeoArea->qualifyColumn('description'),
                $filters["order_type"] ?? 'asc'
            )
            ->select(
                $refSeoArea->qualifyColumn("id"),
                $refSeoArea->qualifyColumn("code"),
                $refSeoArea->qualifyColumn("description"),
                $refSeoArea->qualifyColumn("ref_seo_group_id"),
                $refSeoArea->qualifyColumn("updated_at"),
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
