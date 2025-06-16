<?php

namespace App\Actions\Administrator\Reminder;

use App\Helpers\DatatablesHelper;
use App\Models\RefReminderCategory;
use App\Models\Reminder;

class GetReminderDatatables
{

    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Report Type",
            "name" => "ref_reminder_category.description",
            "orderable" => true,
            "searchable" => true
        ],
        [
            "label" => "When",
            "name" => "when",
            "orderable" => false,
            "searchable" => false,
        ],
        [
            "label" => "Method",
            "name" => "is_manual",
            "orderable" => true,
            "searchable" => true,
            "searchable" => true,
            "searchtype" => "select",
            "options" => [
                [
                    "id" => 0,
                    "label" => "Auto"
                ],
                [
                    "id" => 1,
                    "label" => "Manual"
                ]
            ],
            "isHtml" => true

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
                    "label" => "Disable"
                ],
            ],
        ],
        [
            "label" => "Notes",
            "name" => "notes",
            "orderable" => false,
            "searchable" => false
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
        $category = new RefReminderCategory();
        $reminder = new Reminder();

        return Reminder::query()
            ->join($category->getTable(), function ($join) use ($category, $reminder) {
                return $join->on(
                    $reminder->qualifyColumn("ref_reminder_category_id"),
                    "=",
                    $category->qualifyColumn("id")
                );
            })
            ->when($filters['search_fields'] ?? false, function ($query) use ($filters) {

                $allowedFields = DatatablesHelper::getSearchableField($this->columns);
                foreach ($filters['search_fields'] as $key => $column) {
                    if (!in_array($column, $allowedFields)) {
                        continue;
                    }

                    $search = $filters['search_values'][$key] ?? '';
                    $query->when($search !== '', function ($query) use ($column, $search) {
                        if ($column == 'is_manual' || $column == 'status') {
                            return $query->where($column, $search);
                        }
                        return $query->where($column, 'like', '%' . $search . '%');
                    });
                }
            })
            ->select(
                $reminder->qualifyColumn("id"),
                $reminder->qualifyColumn("ref_reminder_category_id"),
                $reminder->qualifyColumn("is_manual"),
                $reminder->qualifyColumn("scheduled_at"),
                $reminder->qualifyColumn("repeat_type"),
                $reminder->qualifyColumn("options"),
                $reminder->qualifyColumn("notes"),
                $reminder->qualifyColumn("status"),
                $reminder->qualifyColumn("updated_at"),
                $category->qualifyColumn("description")
            )
            ->orderBy($filters["order_by"] ?? $reminder->qualifyColumn('status'), $filters["order_type"] ?? 'desc')
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
