<?php

namespace App\Actions\RefTable\PslkmSub;

use App\Helpers\DatatablesHelper;
use App\Models\RefPslkm;
use App\Models\RefPslkmSub;

class GetPslkmSubDatatables
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
            "label" => "PSLKM",
            "name" => "ref_pslkm.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Description",
            "name" => "description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "Status",
            "name" => "status",
            "orderable" => true,
            "searchable" => false,
            "isHtml" => true
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
        $pslkm = new RefPslkm();
        $sub = new RefPslkmSub();

        return RefPslkmSub::query()
            ->join($pslkm->getTable(), function ($join) use ($pslkm, $sub) {
                return $join->on($pslkm->qualifyColumn("id"), $sub->qualifyColumn("ref_pslkm_id"));
            })
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {
                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) {
                        continue;
                    }
                    $query->when($filters['search_values'][$key] ?? false, function ($query, $search) use ($column) {
                        $query->where($column, 'like', '%' . $search . '%');
                    });
                }
            })
            ->select(
                $sub->qualifyColumn("id"),
                $sub->qualifyColumn("code"),
                $pslkm->qualifyColumn('description') . ' as pslkm',
                $sub->qualifyColumn("description"),
                $sub->qualifyColumn("status"),
                $sub->qualifyColumn("updated_at"),
            )
            ->orderBy($filters["order_by"] ?? $sub->qualifyColumn('description'), $filters["order_type"] ?? 'asc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
