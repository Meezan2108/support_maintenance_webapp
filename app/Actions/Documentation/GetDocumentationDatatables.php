<?php

namespace App\Actions\Documentation;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\Documentation;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\RefOtherDocument;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetDocumentationDatatables
{
    protected $columns = [];

    public function __construct()
    {
        $this->columns = [
            [
                "label" => "",
                "name" => "action",
                "class" => "text-nowrap"
            ],
            [
                "label" => "Category",
                "name" => "ref_other_document.category_desc",
                "orderable" => true,
                "searchable" => true,
                "isHtml" => true,
                "class" => "text-nowrap",
                "searchtype" => "select",
                "options" => collect(RefOtherDocument::get())->map(function ($item, $index) {
                    return [
                        "id" => $item->id,
                        "label" => $item->description
                    ];
                })
            ],
            [
                "label" => "Description",
                "name" => "documentation.description",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "Submission Date",
                "name" => "documentation.submission_date",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Attachment",
                "name" => "files",
                "class" => "text-nowrap",
                "orderable" => false,
                "searchable" => false,
                "isHtml" => true,
            ],
            [
                "label" => "Updated At",
                "name" => "documentation.updated_at",
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
        $documentation = new Documentation();
        $category = new RefOtherDocument();
        $user = new User();

        $data = Documentation::join(
            $user->getTable(),
            function ($join) use ($user, $documentation) {
                $join->on($user->qualifyColumn("id"), $documentation->qualifyColumn("user_id"));
            }
        )
            ->join(
                $category->getTable(),
                function ($join) use ($category, $documentation) {
                    $join->on($category->qualifyColumn("id"), $documentation->qualifyColumn("category"));
                }
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
            ->when($user->hasExactRoles(["Researcher"]), function ($query) use ($documentation) {
                return $query->where($documentation->qualifyColumn("user_id"), Auth::id());
            })
            ->orderBy(
                $filters["order_by"] ?? $documentation->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $documentation->qualifyColumn("id"),
                $user->qualifyColumn('name'),
                $category->qualifyColumn("description as category_desc"),
                $documentation->qualifyColumn("submission_date"),
                $documentation->qualifyColumn("description"),
                $documentation->qualifyColumn("updated_at"),
            )
            ->paginate($filters['per_page'] ?? 20)
            ->withQueryString();

        return $data;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
