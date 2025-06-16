<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    protected $roles = [
        [
            "name" => "Super Admin",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-create',
                'trf-show',
                'trf-edit',
                'trf-delete',
                'trf-comment',

                'external-fund-list',
                'external-fund-create',
                'external-fund-show',
                'external-fund-edit',
                'external-fund-delete',
                'external-fund-comment',

                'application-management-open',

                'technical-evaluation-list',
                'technical-evaluation-create',
                'technical-evaluation-show',
                'technical-evaluation-edit',

                'list-of-approved-list',
                'list-of-approved-show',
                'list-of-approved-create',
                'list-of-approved-revision',

                'list-of-rejected-list',
                'list-of-rejected-show',

                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-create',
                'monitoring-trf-show',
                'monitoring-trf-edit',
                'monitoring-trf-delete',
                'monitoring-trf-approval',

                'monitoring-ef-list',
                'monitoring-ef-create',
                'monitoring-ef-show',
                'monitoring-ef-edit',
                'monitoring-ef-delete',
                'monitoring-ef-approval',

                'extension-project-list',
                'extension-project-create',
                'extension-project-show',
                'extension-project-edit',
                'extension-project-delete',
                'extension-project-approval',

                'end-of-project-list',
                'end-of-project-create',
                'end-of-project-show',
                'end-of-project-edit',
                'end-of-project-delete',
                'end-of-project-approval',

                'research-progress-list',
                'research-progress-create',
                'research-progress-show',
                'research-progress-edit',
                'research-progress-delete',
                'research-progress-approval',

                'research-progress-no-fund-list',
                'research-progress-no-fund-create',
                'research-progress-no-fund-show',
                'research-progress-no-fund-edit',
                'research-progress-no-fund-delete',
                'research-progress-no-fund-approval',

                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-create',
                'publications-show',
                'publications-edit',
                'publications-delete',
                'publications-approval',
                'publications-create-bulk',

                'recognition-list',
                'recognition-create',
                'recognition-show',
                'recognition-edit',
                'recognition-delete',
                'recognition-approval',
                'recognition-create-bulk',

                'rnd-output-list',
                'rnd-output-create',
                'rnd-output-show',
                'rnd-output-edit',
                'rnd-output-delete',
                'rnd-output-approval',
                'rnd-output-create-bulk',

                'ipr-list',
                'ipr-create',
                'ipr-show',
                'ipr-edit',
                'ipr-delete',
                'ipr-approval',
                'ipr-create-bulk',

                'commercialization-list',
                'commercialization-create',
                'commercialization-show',
                'commercialization-edit',
                'commercialization-delete',
                'commercialization-approval',
                'commercialization-create-bulk',

                'analytical-service-lab-list',
                'analytical-service-lab-create',
                'analytical-service-lab-show',
                'analytical-service-lab-edit',
                'analytical-service-lab-delete',
                'analytical-service-lab-approval',
                'analytical-service-lab-create-bulk',

                'imported-germplasm-list',
                'imported-germplasm-create',
                'imported-germplasm-show',
                'imported-germplasm-edit',
                'imported-germplasm-delete',
                'imported-germplasm-approval',
                'imported-germplasm-create-bulk',

                'my-kpi-list',
                'my-kpi-show',

                'target-kpi-list',
                'target-kpi-create',
                'target-kpi-show',
                'target-kpi-edit',
                'target-kpi-delete',
                'target-kpi-approval',

                'target-kpi-global-list',
                'target-kpi-global-create',
                'target-kpi-global-show',
                'target-kpi-global-edit',
                'target-kpi-global-delete',

                'documentation-list',
                'documentation-create',
                'documentation-show',
                'documentation-edit',
                'documentation-delete',

                'administrator-open',

                'user-list',
                'user-create',
                'user-show',
                'user-edit',
                'user-delete',
                'user-approval',

                'role-list',
                'role-create',
                'role-show',
                'role-edit',
                'role-delete',

                'reference-table-list',
                'reference-table-create',
                'reference-table-show',
                'reference-table-edit',
                'reference-table-delete',

                'reminder-list',
                'reminder-create',
                'reminder-show',
                'reminder-edit',
                'reminder-delete',
            ]
        ],
        [
            "name" => "LKM Director",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-show',
                'trf-comment',

                'external-fund-list',
                'external-fund-show',
                'external-fund-comment',

                'application-management-open',

                'technical-evaluation-list',
                'technical-evaluation-create',
                'technical-evaluation-show',
                'technical-evaluation-edit',

                'list-of-approved-list',
                'list-of-approved-show',

                'list-of-rejected-list',
                'list-of-rejected-show',

                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-show',
                'monitoring-trf-approval',

                'monitoring-ef-list',
                'monitoring-ef-show',
                'monitoring-ef-approval',

                'extension-project-list',
                'extension-project-show',
                'extension-project-approval',

                'end-of-project-list',
                'end-of-project-show',
                'end-of-project-approval',

                'research-progress-list',
                'research-progress-show',
                'research-progress-approval',

                'research-progress-no-fund-list',
                'research-progress-no-fund-show',
                'research-progress-no-fund-approval',

                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-show',

                'recognition-list',
                'recognition-show',

                'rnd-output-list',
                'rnd-output-show',

                'ipr-list',
                'ipr-show',

                'commercialization-list',
                'commercialization-show',

                'analytical-service-lab-list',
                'analytical-service-lab-show',

                'imported-germplasm-list',
                'imported-germplasm-show',

                'target-kpi-list',
                'target-kpi-show',

                'documentation-list',
                'documentation-show',

                'administrator-open',

                'user-list',
                'user-show',

                'reference-table-list',
                'reference-table-create',
                'reference-table-show',
            ]
        ],
        [
            "name" => "R&D Coordinator",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-show',
                'trf-comment',

                'external-fund-list',
                'external-fund-show',
                'external-fund-comment',

                'application-management-open',

                'technical-evaluation-list',
                'technical-evaluation-create',
                'technical-evaluation-show',
                'technical-evaluation-edit',

                'list-of-approved-list',
                'list-of-approved-show',

                'list-of-rejected-list',
                'list-of-rejected-show',

                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-show',
                'monitoring-trf-approval',

                'monitoring-ef-list',
                'monitoring-ef-show',
                'monitoring-ef-approval',

                'extension-project-list',
                'extension-project-show',
                'extension-project-approval',

                'end-of-project-list',
                'end-of-project-show',
                'end-of-project-approval',

                'research-progress-list',
                'research-progress-show',
                'research-progress-approval',

                'research-progress-no-fund-list',
                'research-progress-no-fund-show',
                'research-progress-no-fund-approval',

                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-show',

                'recognition-list',
                'recognition-show',

                'rnd-output-list',
                'rnd-output-show',

                'ipr-list',
                'ipr-show',

                'commercialization-list',
                'commercialization-show',

                'analytical-service-lab-list',
                'analytical-service-lab-show',

                'imported-germplasm-list',
                'imported-germplasm-show',

                'target-kpi-list',
                'target-kpi-show',

                'documentation-list',
                'documentation-show',

                'administrator-open',

                'user-list',
                'user-show',

                'reference-table-list',
                'reference-table-create',
                'reference-table-show',
            ]
        ],
        [
            "name" => "Division Director",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-show',
                'trf-comment',

                'external-fund-list',
                'external-fund-show',
                'external-fund-comment',

                'application-management-open',

                'technical-evaluation-list',
                'technical-evaluation-create',
                'technical-evaluation-show',
                'technical-evaluation-edit',

                'list-of-approved-list',
                'list-of-approved-show',

                'list-of-rejected-list',
                'list-of-rejected-show',

                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-show',
                'monitoring-trf-approval',

                'monitoring-ef-list',
                'monitoring-ef-show',
                'monitoring-ef-approval',

                'extension-project-list',
                'extension-project-show',
                'extension-project-approval',

                'end-of-project-list',
                'end-of-project-show',
                'end-of-project-approval',

                'research-progress-list',
                'research-progress-show',
                'research-progress-approval',

                'research-progress-no-fund-list',
                'research-progress-no-fund-show',
                'research-progress-no-fund-approval',

                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-show',

                'recognition-list',
                'recognition-show',

                'rnd-output-list',
                'rnd-output-show',

                'ipr-list',
                'ipr-show',

                'commercialization-list',
                'commercialization-show',

                'analytical-service-lab-list',
                'analytical-service-lab-show',

                'imported-germplasm-list',
                'imported-germplasm-show',

                'target-kpi-list',
                'target-kpi-show',

                'documentation-list',
                'documentation-show',

                'administrator-open',

                'user-list',
                'user-show',

                'reference-table-list',
                'reference-table-create',
                'reference-table-show',
            ]
        ],
        [
            "name" => "RMC",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-show',
                'trf-comment',

                'external-fund-list',
                'external-fund-show',
                'external-fund-comment',

                'application-management-open',

                'technical-evaluation-list',
                'technical-evaluation-create',
                'technical-evaluation-show',
                'technical-evaluation-edit',

                'list-of-approved-list',
                'list-of-approved-show',
                'list-of-approved-create',
                'list-of-approved-revision',

                'list-of-rejected-list',
                'list-of-rejected-show',

                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-show',
                'monitoring-trf-approval',

                'monitoring-ef-list',
                'monitoring-ef-show',
                'monitoring-ef-approval',

                'extension-project-list',
                'extension-project-show',
                'extension-project-approval',

                'end-of-project-list',
                'end-of-project-show',
                'end-of-project-approval',

                'research-progress-list',
                'research-progress-show',
                'research-progress-approval',

                'research-progress-no-fund-list',
                'research-progress-no-fund-show',
                'research-progress-no-fund-approval',

                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-show',
                'publications-approval',

                'recognition-list',
                'recognition-show',
                'recognition-approval',

                'rnd-output-list',
                'rnd-output-show',
                'rnd-output-approval',

                'ipr-list',
                'ipr-show',
                'ipr-approval',

                'commercialization-list',
                'commercialization-show',
                'commercialization-approval',

                'analytical-service-lab-list',
                'analytical-service-lab-show',
                'analytical-service-lab-approval',

                'imported-germplasm-list',
                'imported-germplasm-show',
                'imported-germplasm-approval',

                'target-kpi-list',
                'target-kpi-create',
                'target-kpi-show',
                'target-kpi-edit',
                'target-kpi-delete',

                'documentation-list',
                'documentation-show',

                'administrator-open',

                'user-list',
                'user-show',

                'role-list',
                'role-show',

                'reference-table-list',
                'reference-table-show',
            ]
        ],
        [
            "name" => "Researcher",
            "permissions" => [
                'dashboard-open',
                'my-task-open',

                'management-fund-open',

                'trf-list',
                'trf-create',
                'trf-show',
                'trf-edit',
                'trf-delete',

                'external-fund-list',
                'external-fund-create',
                'external-fund-show',
                'external-fund-edit',
                'external-fund-delete',


                'research-monitoring-open',

                'monitoring-trf-list',
                'monitoring-trf-create',
                'monitoring-trf-show',
                'monitoring-trf-edit',
                'monitoring-trf-delete',

                'monitoring-ef-list',
                'monitoring-ef-create',
                'monitoring-ef-show',
                'monitoring-ef-edit',
                'monitoring-ef-delete',

                'extension-project-list',
                'extension-project-create',
                'extension-project-show',
                'extension-project-edit',
                'extension-project-delete',

                'end-of-project-list',
                'end-of-project-create',
                'end-of-project-show',
                'end-of-project-edit',
                'end-of-project-delete',

                'research-progress-list',
                'research-progress-create',
                'research-progress-show',
                'research-progress-edit',
                'research-progress-delete',

                'research-progress-no-fund-list',
                'research-progress-no-fund-create',
                'research-progress-no-fund-show',
                'research-progress-no-fund-edit',
                'research-progress-no-fund-delete',


                'kpi-monitoring-open',

                'submit-new-kpi-open',

                'publications-list',
                'publications-create',
                'publications-show',
                'publications-edit',
                'publications-delete',

                'recognition-list',
                'recognition-create',
                'recognition-show',
                'recognition-edit',
                'recognition-delete',

                'rnd-output-list',
                'rnd-output-create',
                'rnd-output-show',
                'rnd-output-edit',
                'rnd-output-delete',

                'ipr-list',
                'ipr-create',
                'ipr-show',
                'ipr-edit',
                'ipr-delete',

                'commercialization-list',
                'commercialization-create',
                'commercialization-show',
                'commercialization-edit',
                'commercialization-delete',

                'analytical-service-lab-list',
                'analytical-service-lab-create',
                'analytical-service-lab-show',
                'analytical-service-lab-edit',
                'analytical-service-lab-delete',

                'imported-germplasm-list',
                'imported-germplasm-create',
                'imported-germplasm-show',
                'imported-germplasm-edit',
                'imported-germplasm-delete',

                'my-kpi-list',
                'my-kpi-show',

                'documentation-list',
                'documentation-show',
            ]
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        DB::table("role_has_permissions")->truncate();

        foreach ($this->roles as $role) {
            $permissions = $role['permissions'] ?? [];
            unset($role["permissions"]);

            $modelRole = Role::create($role);

            if ($permissions) {
                $modelRole->givePermissionTo($permissions);
            }
        }
    }
}
