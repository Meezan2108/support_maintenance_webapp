<?php

namespace App\Observers;

use App\Models\Logable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  Model $model
     * @return void
     */
    public function created(Model $model)
    {
        $beforeChange = $model->getOriginal();
        $afterChange = $model->getAttributes();

        $model->logable()->create([
            'action' => Logable::ACTION_CREATE,
            'before_change' => $beforeChange,
            'after_change' => $afterChange,
            'changes' => $model->getChanges(),
            'user_id' => Auth::id() ?? 0,
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  Model $model
     * @return void
     */
    public function updated(Model $model)
    {
        $beforeChange = $model->getOriginal();
        $afterChange = $model->getAttributes();

        $model->logable()->create([
            'action' => Logable::ACTION_UPDATE,
            'before_change' => $beforeChange,
            'after_change' => $afterChange,
            'changes' => $model->getChanges(),
            'user_id' => Auth::id() ?? 0,
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $beforeChange = $model->getOriginal();
        $afterChange = $model->getAttributes();

        $model->logable()->create([
            'action' => Logable::ACTION_DELETE,
            'before_change' => $beforeChange,
            'after_change' => $afterChange,
            'changes' => $model->getChanges(),
            'user_id' => Auth::id() ?? 0,
        ]);
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  Model $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        //
    }
}
