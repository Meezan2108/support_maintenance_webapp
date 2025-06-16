<?php

namespace App\Actions\RefTable\SeoCategory;

use App\Helpers\DatatablesHelper;
use App\Models\RefSeoCategory;
use App\Models\RefSeoSector;

class GetSeoCategoryDatatables
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Code",
            "name" => "ref_seo_category.code",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "ref_seo_category.description",
            "orderable" => true,
            "searchable" => true,
            "class" => "text-wrap min-width-350"
        ],
        [
            "label" => "Sector",
            "name" => "ref_seo_sector.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Updated At",
            "name" => "ref_seo_category.updated_at",
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
        $refSeoSector = new RefSeoSector();

        $user = RefSeoCategory::with("sector")
            ->leftJoin(
                $refSeoSector->getTable(),
                $refSeoCategory->qualifyColumn("ref_seo_sector_id"),
                "=",
                $refSeoSector->qualifyColumn("id")
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
                $filters["order_by"] ?? $refSeoCategory->qualifyColumn('description'),
                $filters["order_type"] ?? 'asc'
            )
            ->select(
                $refSeoCategory->qualifyColumn("id"),
                $refSeoCategory->qualifyColumn("code"),
                $refSeoCategory->qualifyColumn("description"),
                $refSeoCategory->qualifyColumn("ref_seo_sector_id"),
                $refSeoCategory->qualifyColumn("updated_at"),
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
