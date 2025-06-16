<?php

namespace App\Policies;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can("reminder-list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Reminder  $model
     * @return bool
     */
    public function view(User $user, Reminder $model): bool
    {
        return $user->can("reminder-show");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can("reminder-create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Reminder  $model
     * @return bool
     */
    public function update(User $user, Reminder $model): bool
    {
        return $user->can("reminder-edit");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Reminder  $model
     * @return bool
     */
    public function delete(User $user, Reminder $model): bool
    {
        return $user->can("reminder-delete");
    }
}
