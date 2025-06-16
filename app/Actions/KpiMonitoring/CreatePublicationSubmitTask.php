<?php

namespace App\Actions\KpiMonitoring;

use App\Actions\CreateTask;
use App\Models\Publication;
use App\Models\Taskable;
use App\Models\User;

class CreatePublicationSubmitTask
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Publication $publication, User $createdByUser)
    {
        (new CreateTask)->execute(
            $publication,
            $createdByUser,
            User::ROLE_RMC,
            Taskable::TARGET_TYPE_GROUP,
            route("panel.publications.approvement", ["publication" => $publication->id]),
            "-",
            $publication->title,
            'KPI Publications',
            $publication->kpiAchievement->approval_status
        );
    }
}
