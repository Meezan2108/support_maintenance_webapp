<?php

namespace App\Http\Controllers;

use App\Actions\Notification\GetNotification;
use App\Actions\Notification\MarkAllReadNotification;
use App\Actions\Notification\ReadNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyTask\MyTaskSearchRequest;
use App\Http\Resources\Notification\NotificationTableResource;
use App\Models\Notifable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $policy;

    public function index(GetNotification $datatables, MyTaskSearchRequest $request)
    {
        $user = User::find(Auth::id());

        $filters = $request->validated();
        $data = $datatables->execute($user, $filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Notifications/Index', [
            "title" => "Notification",
            "additional" => [
                "data" => NotificationTableResource::collection($data),
                "filters" => $filters,
            ]
        ]);
    }

    public function update(Request $request, Notifable $notification, ReadNotification $readNotification)
    {
        $user = User::find(Auth::id());

        $readNotification->execute($user, $notification);

        return response([
            "message" => "Update as read success!"
        ], 200);
    }

    public function updateAllAsRead(Request $request, MarkAllReadNotification $markAllReadNotification)
    {
        $user = User::find(Auth::id());
        $markAllReadNotification->execute($user);

        return response([
            "message" => "Updatee all as read success"
        ], 200);
    }
}
