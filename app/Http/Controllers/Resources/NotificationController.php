<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Notification\GetCountUnreadNotification;
use App\Actions\Notification\GetNotificationNavbar;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userAuth = User::find(Auth::id());

        $notifications = (new GetNotificationNavbar)->execute($userAuth);

        return response([
            'message' => 'Notifications data',
            'data' => $notifications
        ], 200);
    }

    public function count(Request $request)
    {
        $userAuth = User::find(Auth::id());

        $count = (new GetCountUnreadNotification)->execute($userAuth);

        return response([
            'message' => 'Count Notifications',
            'data' => $count
        ], 200);
    }
}
