<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword', [
            'url_submit' => route('forgot-password')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(128);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        SendEmailResetPassword::dispatch($request->email, $token);

        return back()->with('message', [
            "status" => "success",
            "message" => "We have sent a password reset link to your email!"
        ]);
    }
}
