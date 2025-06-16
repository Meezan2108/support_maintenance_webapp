<?php

namespace App\Actions\User;

use App\Actions\ActionLog\LogAction;
use App\Models\ActionLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncUser
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return User
     * @throws Throwable
     */
    public function execute(array $data): User
    {
        /** @var User|null $user */
        $user = User::where("code", $data['code'])->first();

        return DB::transaction(function () use ($data, $user) {

            if (!$user) {
                /** @var User $user */
                $user = User::create([
                    "name" => $data['name'],
                    "code" => $data['code'],
                    "ref_division_id" => $data['ref_division_id'],
                    "ref_position_id" => $data['ref_position_id'],
                    "tel_no" => $data['tel_no'],
                    "fax_no" => $data['fax_no'],
                    "email" => $data['email']
                ]);
            } else {
                $user->update([
                    "name" => $data['name'],
                    "code" => $data['code'],
                    "ref_division_id" => $data['ref_division_id'],
                    "ref_position_id" => $data['ref_position_id'],
                    "tel_no" => $data['tel_no'],
                    "fax_no" => $data['fax_no'],
                    "email" => $data['email']
                ]);
            }

            $roles = $data["roles"];
            $user->assignRole($roles);

            return $user;
        });
    }
}
