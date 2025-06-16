<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    public function edit($token)
    {
        $resetData = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(3))
            ->first();

        if (!$resetData) {
            abort(401);
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $resetData->email,
            'url_submit' => route('reset-password.submit')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

        if (!$updatePassword) {
            return back()->withInput()->with('message', [
                "status" => "error",
                "message" => 'Invalid Token!'
            ]);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => password_hash($request->password, PASSWORD_DEFAULT)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('login')
            ->with('message', [
                "status" => "success",
                "message" => 'Your password has been changed!'
            ]);
    }
}
