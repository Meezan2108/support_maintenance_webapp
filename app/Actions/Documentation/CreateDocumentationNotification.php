<?php

namespace App\Actions\Documentation;

use App\Actions\Notification\CreateNotification;
use App\Models\Documentation;
use App\Models\Notifable;
use App\Models\Template;
use App\Models\User;

class CreateDocumentationNotification
{

    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Documentation $documentation)
    {
        // dd($documentation->category);
        $options = [
            "title" => $documentation->refCategory->description,
            "link" => route("panel.documentation.show", ["documentation" => $documentation->id])
        ];

        $notification = (new CreateNotification)->execute(
            $documentation,
            Notifable::TARGET_TYPE_GROUP,
            User::ROLE_RESEARCHER,
            Template::TYPE_NEW_DOCUMENTATION,
            $options
        );

        return $notification;
    }
}
