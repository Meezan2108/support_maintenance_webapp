<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileCredentialsRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VueSelect\RefSelectDefaultResource;
use App\Models\Fileable;
use App\Models\RefDivision;
use App\Models\RefPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $user = $user->load("roles", "division", "position", "fileable");

        return Inertia::render('Profile/Show', [
            "user" => (new UserResource($user))->toArray($request),
            "canEdit" => true,
            "urlEdit" => route("panel.profile.edit")
        ]);
    }

    public function edit(Request $request)
    {
        $user = User::find(Auth::id());

        $divisions = RefDivision::where("is_active", 1)->get();
        $positions = RefPosition::all();

        return Inertia::render('Profile/Edit', [
            "user" => (new UserResource($user->load("roles")))->toArray($request),
            "hasPassword" => $user->password ? true : false,
            "divisions" => RefSelectDefaultResource::collection($divisions),
            "positions" => RefSelectDefaultResource::collection($positions),

            "canView" => true,

            "urlShow" => route("panel.profile"),
            "urlUpdate" => route("panel.profile.update"),
            "urlUpdateCreds" => route("panel.profile.update-creds")
        ]);
    }

    public function update(ProfileRequest $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

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
                    $user->fileable()
                        ->where("code_type", User::FILEABLE_PROFILE_CODE)
                        ->update($fileableFormat);
                } else {
                    $user->fileable()->create(array_merge([
                        "code_type" => User::FILEABLE_PROFILE_CODE,
                        "access_key" => Str::random(64)
                    ], $fileableFormat));
                }
            }

            $arrUser["updated_by"] = Auth::id();

            $user->update($arrUser);
        });

        return redirect()->route("panel.profile")
            ->with("message", [
                "status" => "success",
                "message" => "Update Profile Success!"
            ]);
    }

    public function updateCredentials(ProfileCredentialsRequest $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $arrUser = $request->validated();

        // check old password
        if ($user->password && !Hash::check($arrUser["password_old"], $user->password)) {
            throw ValidationException::withMessages([
                "password_old" => 'Old password not match!'
            ]);
        }

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
                "message" => "Update Profile Credentials Success!"
            ]);
    }
}
