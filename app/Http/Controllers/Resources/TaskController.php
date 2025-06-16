<?php

namespace App\Http\Controllers\Resources;

use App\Actions\MyTask\GetCountMyTask;
use App\Actions\Notification\GetCountUnreadNotification;
use App\Actions\Notification\GetNotificationNavbar;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function count(Request $request)
    {
        $userAuth = User::find(Auth::id());

        $count = (new GetCountMyTask)->execute($userAuth);

        return response([
            'message' => 'Count My Task',
            'data' => $count
        ], 200);
    }
}
