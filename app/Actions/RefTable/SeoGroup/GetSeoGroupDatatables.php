<?php

namespace App\Actions\RefTable\SeoGroup;

use App\Helpers\DatatablesHelper;
use App\Models\RefSeoCategory;
use App\Models\RefSeoGroup;
use App\Models\RefSeoSector;

class GetSeoGroupDatatables
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "ref_seo_group.code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "ref_seo_group.description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Category",
            "name" => "ref_seo_category.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Updated At",
            "name" => "ref_seo_group.updated_at",
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
        $refSeoCategory = new RefSeoCategory();
        $refSeoGroup = new RefSeoGroup();

        $user = RefSeoGroup::with("category")
            ->leftJoin(
                $refSeoCategory->getTable(),
                $refSeoGroup->qualifyColumn("ref_seo_category_id"),
                "=",
                $refSeoCategory->qualifyColumn("id")
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
                $filters["order_by"] ?? $refSeoGroup->qualifyColumn('description'),
                $filters["order_type"] ?? 'asc'
            )
            ->select(
                $refSeoGroup->qualifyColumn("id"),
                $refSeoGroup->qualifyColumn("code"),
                $refSeoGroup->qualifyColumn("description"),
                $refSeoGroup->qualifyColumn("ref_seo_category_id"),
                $refSeoGroup->qualifyColumn("updated_at"),
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
