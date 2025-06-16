<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;

class NotificationTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "data" => $this->data,
            "description" => $this->generateTemplate(),
            "log" => $this->log,
            "isRead" => $this->checkIsRead(),
            "created_at" => $this->created_at
        ];
    }

    private function generateTemplate()
    {
        return Blade::render($this->template->template, $this->data);
    }

    private function checkIsRead()
    {
        if (!$this->log->count()) return false;

        return $this->log[0]->status;
    }
}
