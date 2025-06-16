<?php

namespace App\Http\Controllers\Master;

use App\Actions\Menu\BuildMenu;
use App\Actions\Menu\GetMenusWithPermission;
use App\Actions\Role\GetRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleSearchRequest;
use App\Http\Resources\RoleResource;
use App\Policies\RolePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $columns = [
        [
            "label" => "",
            "name" => "action",
            "class" => "text-nowrap"
        ],
        [
            "label" => "Nama",
            "name" => "name",
            "orderable" => true,
        ],
        [
            "label" => "Updated At",
            "name" => "updated_at",
            "orderable" => true,
            "isDateTime" => true,
        ],
    ];

    protected $getMenusWithPermission;
    protected $buildMenu;
    protected $rolePolicy;

    public function __construct(
        GetMenusWithPermission $getMenusWithPermission,
        BuildMenu $buildMenu,
        RolePolicy $rolePolicy
    ) {
        $this->getMenusWithPermission = $getMenusWithPermission;
        $this->buildMenu = $buildMenu;
        $this->rolePolicy = $rolePolicy;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetRoles $getRoles, RoleSearchRequest $request)
    {
        $this->authorize('viewAny', Role::class);

        $filters = $request->validated();
        $roles = $getRoles->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/Role/Index', [
            "roles" => RoleResource::collection($roles),
            "filters" => $filters,
            "columns" => $this->columns,
            "canCreate" => $this->rolePolicy->create(Auth::user()),
            "urlCreate" => route("panel.role.create"),
            "urlIndex" => route("panel.role.index")
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Role::class);

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Role/Create', [
            "filters" => $filters,
            "menulist" => $this->getMenusWithPermission->execute(),
            "urlStore" => route("panel.role.store"),
            "urlIndex" => route("panel.role.index"),
            "locationPermissions" => \Spatie\Permission\Models\Permission::where('name', 'locations-list')->pluck('name'),
        ]);
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $role = DB::transaction(function () use ($request) {
            $role = Role::create([
                "name" => $request->name
            ]);

            $role->permissions()
                ->sync($request->selPermission);

            return $role;
        });

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.role.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Add Role $role->name Success!"
            ]);
    }

    public function show(Request $request, Role $role)
    {
        $this->authorize('view', $role);

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Role/Show', [
            "filters" => $filters,
            "role" => $role->load("permissions"),
            "menulist" => $this->getMenusWithPermission->execute(),
            "canEdit" => $this->rolePolicy->update(Auth::user(), $role),
            "urlEdit" => route("panel.role.edit", $role),
            "urlIndex" => route("panel.role.index")
        ]);
    }

    public function edit(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Role/Edit', [
            "filters" => $filters,
            "role" => $role->load("permissions"),
            "menulist" => $this->getMenusWithPermission->execute(),
            "canView" => $this->rolePolicy->view(Auth::user(), $role),
            "urlShow" => route("panel.role.show", $role),
            "urlIndex" => route("panel.role.index"),
            "urlUpdate" => route("panel.role.update", $role),
            "locationPermissions" => \Spatie\Permission\Models\Permission::where('name', 'locations-list')->pluck('name'),
        ]);
    }

    public function update(Role $role, RoleRequest $request)
    {
        $this->authorize('update', $role);
        DB::transaction(function () use ($role, $request) {
            $role->name = $request->name;
            $role->permissions()->sync($request->selPermission);
            $role->save();

            $this->buildMenu->execute([$role->id], true);
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.role.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Role $role->name Success!"
            ]);
    }

    public function destroy(Role $role, Request $request)
    {
        $this->authorize('delete', $role);

        $roleName = $role->name;
        DB::transaction(function () use ($role) {
            $role->permissions()->sync([]);
            $role->delete();
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.role.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Delete Role $roleName Success!"
            ]);
    }
}
