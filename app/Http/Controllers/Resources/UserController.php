<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $userAuth = User::find(Auth::id());

        $users = User::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->select('id', 'name as description')
            ->when(!$userAuth->hasRole(['RMC', 'Super Admin']) && $request->show_all != 1, function ($query) use ($userAuth) {
                return $query->where('id', $userAuth->id);
            })
            ->when($request->roles, function ($query) use ($request) {
                return $query->whereHas("roles", function ($query) use ($request) {
                    return $query->whereIn("roles.id", $request->roles);
                });
            })
            ->limit(20)
            ->get();

        return response([
            'message' => 'Search Users',
            'data' => $users
        ], 200);
    }

    public function show(Request $request, User $user)
    {
        $userAuth = User::find(Auth::id());

        if (
            !$userAuth->hasRole(['RMC', 'Super Admin'])
            && $userAuth->id != $user->id
            && $request->show_all != 1
        ) {
            abort(403);
        }

        $user->description = $user->name;

        return response([
            'message' => 'Show Users',
            'data' => $user
        ], 200);
    }
}
