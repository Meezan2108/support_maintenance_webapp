<?php

namespace App\Actions\MonitoringFile;

use App\Helpers\DatatablesHelper;
use App\Models\Approvement;
use App\Models\Documentation;
use App\Models\Fileable;
use App\Models\KpiAchievement;
use App\Models\OutputRnd;
use App\Models\Recognition;
use App\Models\RefOtherDocument;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GetMonitoringFileDatatables
{
    protected $columns = [];

    public function __construct()
    {
        $this->columns = [
            [
                "label" => "Attachment",
                "name" => "files",
                "class" => "text-nowrap",
                "orderable" => false,
                "searchable" => false,
                "isHtml" => true,
            ],
            [
                "label" => "Fileable Type",
                "name" => "fileable.fileable_type",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Fileable ID",
                "name" => "fileable.fileable_id",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Code Type",
                "name" => "fileable.code_type",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "File Name",
                "name" => "fileable.file_name",
                "orderable" => true,
                "searchable" => true,
                "class" => "text-wrap min-width-350"
            ],
            [
                "label" => "File Type",
                "name" => "fileable.file_type",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "File Size",
                "name" => "fileable.file_size",
                "orderable" => true,
                "searchable" => true
            ],
            [
                "label" => "Updated At",
                "name" => "fileable.updated_at",
                "orderable" => true,
                "searchable" => false
            ],
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
        $fileable = new Fileable();
        $user = new User();

        $data = Fileable::when($filters['search_fields'] ?? false, function ($query) use ($filters) {
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
                $filters["order_by"] ?? $fileable->qualifyColumn('updated_at'),
                $filters["order_type"] ?? 'desc'
            )
            ->select(
                $fileable->qualifyColumn("id"),
                $fileable->qualifyColumn("fileable_type"),
                $fileable->qualifyColumn("fileable_id"),
                $fileable->qualifyColumn("access_key"),
                $fileable->qualifyColumn("code_type"),
                $fileable->qualifyColumn("file_name"),
                $fileable->qualifyColumn("file_type"),
                $fileable->qualifyColumn("file_size"),
                $fileable->qualifyColumn("created_at"),
                $fileable->qualifyColumn("updated_at"),
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
