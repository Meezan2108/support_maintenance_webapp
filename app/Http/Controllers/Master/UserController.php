<?php

namespace App\Http\Controllers\Master;

use App\Actions\User\GetUsersDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCredentialsRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\VueSelect\RefSelectDefaultResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserTableResource;
use App\Http\Resources\VueSelect\RoleSelectResource;
use App\Models\Fileable;
use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{

    protected $userPolicy;

    public function __construct(UserPolicy $userPolicy)
    {
        $this->userPolicy = $userPolicy;
    }

    public function index(GetUsersDatatables $getUsers, UserSearchRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->validated();
        $users = $getUsers->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/User/Index', [
            "users" => UserTableResource::collection($users),
            "filters" => $filters,
            "columns" => $getUsers->getColumns(),
            "canCreate" => $this->userPolicy->create(Auth::user()),
            "urlCreate" => route("panel.user.create"),
            "urlIndex" => route("panel.user.index")
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        $divisions = RefDivision::where("is_active", 1)->get();
        $positions = RefPosition::all();
        $roles = Role::all();

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/User/Create', [
            "filters" => $filters,
            "divisions" => RefSelectDefaultResource::collection($divisions),
            "positions" => RefSelectDefaultResource::collection($positions),
            "roles" => RoleSelectResource::collection($roles),
            "urlStore" => route("panel.user.store"),
            "urlIndex" => route("panel.user.index")
        ]);
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        DB::transaction(function () use ($request) {
            $arrUser = $request->validated();
            $arrUser["password"] = Hash::make($arrUser["password"]);
            $arrUser["created_by"] = Auth::id();
            $user = User::create($arrUser);

            $user->save();
            // upload
            if ($request->hasFile('file_picture')) {
                $requestFile = $request->file('file_picture');

                $fileableFormat = Fileable::prepareForDB($requestFile);

                $user->fileable()->create(array_merge([
                    "code_type" => User::FILEABLE_PROFILE_CODE,
                    "access_key" => Str::random(64)
                ], $fileableFormat));
            }

            $user->roles()->sync($arrUser['roles']);
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.user.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update User Success!"
            ]);
    }

    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/User/Show', [
            "user" => (new UserResource($user->load("roles", "division", "position")))->toArray($request),
            "filters" => $filters,
            "canEdit" => $this->userPolicy->update(Auth::user(), $user),
            "urlEdit" => route("panel.user.edit", $user),
            "urlIndex" => route("panel.user.index")
        ]);
    }

    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $divisions = RefDivision::whereNotNull("code2")->get();
        $positions = RefPosition::all();
        $roles = Role::all();

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/User/Edit', [
            "user" => (new UserResource($user->load("roles")))->toArray($request),
            "filters" => $filters,
            "divisions" => RefSelectDefaultResource::collection($divisions),
            "positions" => RefSelectDefaultResource::collection($positions),
            "roles" => RoleSelectResource::collection($roles),
            "canView" => $this->userPolicy->view(Auth::user(), $user),

            "urlShow" => route("panel.user.show", $user),
            "urlUpdate" => route("panel.user.update", $user),
            "urlIndex" => route("panel.user.index"),
            "urlUpdateCreds" => route("panel.user.update-credentials", $user)
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        DB::transaction(function () use ($request, $user) {
            $arrUser = $request->validated();

            if (!$request->old_picture && !$request->hasFile('file_picture')) {
                $user->fileable()->delete();
            }

            // upload
            if ($request->hasFile('file_picture')) {
                $requestFile = $request->file('file_picture');

                $fileableFormat = Fileable::prepareForDB($requestFile);

                $fileable = $user->fileable;

                if ($fileable) {
                    $fileableFormat["access_key"] = Str::random(64);
                    $fileable->update($fileableFormat);
                } else {
                    $user->fileable()->create(array_merge([
                        "code_type" => User::FILEABLE_PROFILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));
                }
            }

            $arrUser["updated_by"] = Auth::id();

            $user->update($arrUser);
            $user->roles()->sync($arrUser['roles']);
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.user.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update User Success!"
            ]);
    }

    public function updateCredentials(UserCredentialsRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $arrUser = $request->validated();

        DB::transaction(function () use ($arrUser, $user) {
            $user->email = $arrUser["email"];
            if ($arrUser["password"]) {
                $user->password = Hash::make($arrUser["password"]);
            }
            $user->updated_by = Auth::id();
            $user->save();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Update User Credentials Success!"
            ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $name = $user->name;
        DB::transaction(function () use ($user) {
            $user->deleted_by = Auth::id();
            $user->save();
            $user->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete User '$name' Success!"
            ]);
    }
}
