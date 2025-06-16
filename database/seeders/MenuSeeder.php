<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    protected $arrMenu = [
        [
            "parent_id" => 0,
            "code" => "dashboard",
            "name" => "Dashboard",
            "description" => "",
            "icon" => "dashboard",
            "order" => '01',
            "type" => 0,
            "permission" => [
                'dashboard-open'
            ]
            ],
        [
            "parent_id" => 0,
            "code" => "locations",
            "name" => "Locations",
            "description" => "",
            "icon" => "",
            "order" => '01',
            "type" => 0,
            "permission" => [
                'locations-list'
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "my-task",
            "name" => "My Task",
            "description" => "",
            "icon" => "playlist_add_check_circle",
            "order" => '02',
            "type" => 0,
            "permission" => [
                'my-task-open'
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "management-fund",
            "name" => "Support & Maintenance",
            "description" => "",
            "icon" => "edit_document",
            "order" => '03',
            "type" => 2,
            "permission" => [
                'management-fund-open'
            ],
            "submenu" => [
                [
                    "parent_id" => 0,
                    "code" => "trf",
                    "name" => "Support & Maintenance List",
                    "description" => "",
                    "icon" => "",
                    "order" => '01',
                    "type" => 0,
                    "permission" => [
                        'trf-list',
                        'trf-create',
                        'trf-show',
                        'trf-edit',
                        'trf-delete',
                        'trf-comment'
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "external-fund",
                    "name" => "External Fund",
                    "description" => "",
                    "icon" => "",
                    "order" => '02',
                    "type" => 0,
                    "permission" => [
                        'external-fund-list',
                        'external-fund-create',
                        'external-fund-show',
                        'external-fund-edit',
                        'external-fund-delete',
                        'external-fund-comment'
                    ],
                ],
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "application-management",
            "name" => "Profiling",
            "description" => "",
            "icon" => "manage_accounts",
            "order" => '04',
            "type" => 2,
            "permission" => [
                'application-management-open'
            ],
            "submenu" => [
                [
                    "parent_id" => 0,
                    "code" => "technical-evaluation",
                    "name" => "Client List",
                    "description" => "",
                    "icon" => "",
                    "order" => '01',
                    "type" => 0,
                    "permission" => [
                        'technical-evaluation-list',
                        'technical-evaluation-create',
                        'technical-evaluation-show',
                        'technical-evaluation-edit',
                        'technical-evaluation-delete'
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "list-of-approved",
                    "name" => "List of Projects",
                    "description" => "",
                    "icon" => "",
                    "order" => '02',
                    "type" => 0,
                    "permission" => [
                        'list-of-approved-list',
                        'list-of-approved-show',
                        'list-of-approved-create',
                        'list-of-approved-revision',
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "list-of-rejected",
                    "name" => "Client List",
                    "description" => "",
                    "icon" => "",
                    "order" => '03',
                    "type" => 0,
                    "permission" => [
                        'list-of-rejected-list',
                        'list-of-rejected-show',
                    ],
                ],
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "research-monitoring",
            "name" => "Research Project Monitoring",
            "description" => "",
            "icon" => "query_stats",
            "order" => '05',
            "type" => 2,
            "permission" => [
                'research-monitoring-open',
            ],
            "submenu" => [
                [
                    "code" => "monitoring-trf",
                    "name" => "Support & Maintenance List",
                    "description" => "",
                    "icon" => "",
                    "order" => '01',
                    "type" => 0,
                    "permission" => [
                        'monitoring-trf-list',
                        'monitoring-trf-create',
                        'monitoring-trf-show',
                        'monitoring-trf-edit',
                        'monitoring-trf-delete',
                        'monitoring-trf-approval',
                    ],
                ],
                [
                    "code" => "monitoring-ef",
                    "name" => "External Fund",
                    "description" => "",
                    "icon" => "",
                    "order" => '02',
                    "type" => 0,
                    "permission" => [
                        'monitoring-ef-list',
                        'monitoring-ef-create',
                        'monitoring-ef-show',
                        'monitoring-ef-edit',
                        'monitoring-ef-delete',
                        'monitoring-ef-approval',
                    ],
                ],
                [
                    "code" => "extension-project",
                    "name" => "Extension of Project",
                    "description" => "",
                    "icon" => "",
                    "order" => '03',
                    "type" => 0,
                    "permission" => [
                        'extension-project-list',
                        'extension-project-create',
                        'extension-project-show',
                        'extension-project-edit',
                        'extension-project-delete',
                        'extension-project-approval',
                    ],
                ],
                [
                    "code" => "end-of-project",
                    "name" => "End of Project",
                    "description" => "",
                    "icon" => "",
                    "order" => '04',
                    "type" => 0,
                    "permission" => [
                        'end-of-project-list',
                        'end-of-project-create',
                        'end-of-project-show',
                        'end-of-project-edit',
                        'end-of-project-delete',
                        'end-of-project-approval',
                    ],
                ],
                [
                    "code" => "research-progress",
                    "name" => "Research Progress Report",
                    "description" => "",
                    "icon" => "",
                    "order" => '05',
                    "type" => 0,
                    "permission" => [
                        'research-progress-list',
                        'research-progress-create',
                        'research-progress-show',
                        'research-progress-edit',
                        'research-progress-delete',
                        'research-progress-approval',
                    ],
                ],
                [
                    "code" => "research-progress-no-fund",
                    "name" => "Research Progress Report (No Fund)",
                    "description" => "",
                    "icon" => "",
                    "order" => '05',
                    "type" => 0,
                    "permission" => [
                        'research-progress-no-fund-list',
                        'research-progress-no-fund-create',
                        'research-progress-no-fund-show',
                        'research-progress-no-fund-edit',
                        'research-progress-no-fund-delete',
                        'research-progress-no-fund-approval',
                    ],
                ],
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "kpi-monitoring",
            "name" => "R&D LKM KPI Monitoring",
            "description" => "",
            "icon" => "bar_chart",
            "order" => '06',
            "type" => 2,
            "permission" => [
                'kpi-monitoring-open',
            ],
            "submenu" => [
                [
                    "code" => "submit-new-kpi",
                    "name" => "Submit KPI Achievement",
                    "description" => "",
                    "icon" => "",
                    "order" => '01',
                    "type" => 2,
                    "permission" => [
                        'submit-new-kpi-open',
                    ],
                    "submenu" => [
                        [
                            "code" => "publications",
                            "name" => "Publications",
                            "description" => "",
                            "icon" => "",
                            "order" => '01',
                            "type" => 0,
                            "permission" => [
                                'publications-list',
                                'publications-create',
                                'publications-show',
                                'publications-edit',
                                'publications-delete',
                                'publications-approval',
                                'publications-create-bulk',
                            ],
                        ],
                        [
                            "code" => "recognition",
                            "name" => "Recognition",
                            "description" => "",
                            "icon" => "",
                            "order" => '02',
                            "type" => 0,
                            "permission" => [
                                'recognition-list',
                                'recognition-create',
                                'recognition-show',
                                'recognition-edit',
                                'recognition-delete',
                                'recognition-approval',
                                'recognition-create-bulk',
                            ],
                        ],
                        [
                            "code" => "rnd-output",
                            "name" => "R&D Output",
                            "description" => "",
                            "icon" => "",
                            "order" => '03',
                            "type" => 0,
                            "permission" => [
                                'rnd-output-list',
                                'rnd-output-create',
                                'rnd-output-show',
                                'rnd-output-edit',
                                'rnd-output-delete',
                                'rnd-output-approval',
                                'rnd-output-create-bulk',
                            ],
                        ],
                        [
                            "code" => "ipr",
                            "name" => "Intellectual Property Right",
                            "description" => "",
                            "icon" => "",
                            "order" => '04',
                            "type" => 0,
                            "permission" => [
                                'ipr-list',
                                'ipr-create',
                                'ipr-show',
                                'ipr-edit',
                                'ipr-delete',
                                'ipr-approval',
                                'ipr-create-bulk',
                            ],
                        ],
                        [
                            "code" => "commercialization",
                            "name" => "Commercialization",
                            "description" => "",
                            "icon" => "",
                            "order" => '05',
                            "type" => 0,
                            "permission" => [
                                'commercialization-list',
                                'commercialization-create',
                                'commercialization-show',
                                'commercialization-edit',
                                'commercialization-delete',
                                'commercialization-approval',
                                'commercialization-create-bulk',
                            ],
                        ],
                        [
                            "code" => "analytical-service-lab",
                            "name" => "Analytical Service Lab",
                            "description" => "",
                            "icon" => "",
                            "order" => '06',
                            "type" => 0,
                            "permission" => [
                                'analytical-service-lab-list',
                                'analytical-service-lab-create',
                                'analytical-service-lab-show',
                                'analytical-service-lab-edit',
                                'analytical-service-lab-delete',
                                'analytical-service-lab-approval',
                                'analytical-service-lab-create-bulk',
                            ],
                        ],
                        [
                            "code" => "imported-germplasm",
                            "name" => "Imported Germplasm",
                            "description" => "",
                            "icon" => "",
                            "order" => '07',
                            "type" => 0,
                            "permission" => [
                                'imported-germplasm-list',
                                'imported-germplasm-create',
                                'imported-germplasm-show',
                                'imported-germplasm-edit',
                                'imported-germplasm-delete',
                                'imported-germplasm-approval',
                                'imported-germplasm-create-bulk',
                            ],
                        ],
                    ]
                ],
                [
                    "code" => "my-kpi",
                    "name" => "My KPI Achievement",
                    "description" => "",
                    "icon" => "",
                    "order" => '02',
                    "type" => 0,
                    "permission" => [
                        'my-kpi-list',
                        'my-kpi-show',
                    ],
                ],
                [
                    "code" => "target-kpi",
                    "name" => "Agency KPI Target",
                    "description" => "",
                    "icon" => "",
                    "order" => '03',
                    "type" => 0,
                    "permission" => [
                        'target-kpi-list',
                        'target-kpi-create',
                        'target-kpi-show',
                        'target-kpi-edit',
                        'target-kpi-delete',
                        'target-kpi-approval',
                    ],
                ],
                [
                    "code" => "target-kpi-global",
                    "name" => "Global KPI Target",
                    "description" => "",
                    "icon" => "",
                    "order" => '04',
                    "type" => 0,
                    "permission" => [
                        'target-kpi-global-list',
                        'target-kpi-global-create',
                        'target-kpi-global-show',
                        'target-kpi-global-edit',
                        'target-kpi-global-delete',
                    ],
                ]
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "documentation",
            "name" => "Documentation",
            "description" => "",
            "icon" => "bookmark",
            "order" => '07',
            "type" => 0,
            "permission" => [
                'documentation-list',
                'documentation-create',
                'documentation-show',
                'documentation-edit',
                'documentation-delete',
            ]
        ],
        [
            "parent_id" => 0,
            "code" => "administrator",
            "name" => "Administrator",
            "description" => "",
            "icon" => "admin_panel_settings",
            "order" => '08',
            "type" => 2,
            "permission" => [
                'administrator-open'
            ],
            "submenu" => [
                [
                    "parent_id" => 0,
                    "code" => "user",
                    "name" => "User",
                    "description" => "",
                    "icon" => "",
                    "order" => '01',
                    "type" => 0,
                    "permission" => [
                        'user-list',
                        'user-create',
                        'user-show',
                        'user-edit',
                        'user-delete',
                        'user-approval'
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "role",
                    "name" => "Role",
                    "description" => "",
                    "icon" => "",
                    "order" => '02',
                    "type" => 0,
                    "permission" => [
                        'role-list',
                        'role-create',
                        'role-show',
                        'role-edit',
                        'role-delete',
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "reference-table",
                    "name" => "Location",
                    "description" => "",
                    "icon" => "",
                    "order" => '03',
                    "type" => 0,
                    "permission" => [
                        'reference-table-list',
                        'reference-table-create',
                        'reference-table-show',
                        'reference-table-edit',
                        'reference-table-delete',
                    ],
                ],
                [
                    "parent_id" => 0,
                    "code" => "reminder",
                    "name" => "ST Members",
                    "description" => "",
                    "icon" => "",
                    "order" => '04',
                    "type" => 0,
                    "permission" => [
                        'reminder-list',
                        'reminder-create',
                        'reminder-show',
                        'reminder-edit',
                        'reminder-delete',
                    ],
                ],
                                [
                    "parent_id" => 0,
                    "code" => "locations",
                    "name" => "Locations",
                    "description" => "",
                    "icon" => "",
                    "order" => '05',
                    "type" => 0,
                    "permission" => [
                        'locations-list'
                    ],
                ],
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();
        Permission::truncate();
        DB::table('menu_has_permission')->truncate();

        foreach ($this->arrMenu as $menu) {
            $permissions = collect($menu['permission']);
            $submenus = $menu['submenu'] ?? [];

            unset($menu["permission"]);
            unset($menu["submenu"]);

            $menu["order"] = $menu["order"] . "00";
            $menu = Menu::create($menu);
            $permissions = $permissions->map(function ($item) {
                return [
                    'name' => $item
                ];
            });

            foreach ($permissions as $permission) {
                $menu->permission()->create($permission);
            }

            foreach ($submenus as $submenu) {
                $permissions = collect($submenu['permission']);
                $subSubmenus = $submenu['submenu'] ?? [];

                unset($submenu["permission"]);
                unset($submenu["submenu"]);

                $submenu["order"] = substr($menu->order, 0, 2) . $submenu["order"];
                $submenu['parent_id'] = $menu->id;
                $submenu = Menu::create($submenu);
                $permissions = $permissions->map(function ($item) {
                    return [
                        'name' => $item
                    ];
                });

                foreach ($permissions as $permission) {
                    $submenu->permission()->create($permission);
                }


                foreach ($subSubmenus as $subSubmenu) {
                    $permissions = collect($subSubmenu['permission']);

                    unset($subSubmenu["permission"]);

                    $subSubmenu["order"] = substr($submenu->order, 0, 4) . $subSubmenu["order"];
                    $subSubmenu['parent_id'] = $submenu->id;
                    $subSubmenu = Menu::create($subSubmenu);
                    $permissions = $permissions->map(function ($item) {
                        return [
                            'name' => $item
                        ];
                    });

                    foreach ($permissions as $permission) {
                        $subSubmenu->permission()->create($permission);
                    }
                }
            }
        }
    }
}
