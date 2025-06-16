<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Auth::viaRemember();
        Auth::check();
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        return Inertia::render("Auth/Login");
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials, $remember_me)) {
            $request->session()->regenerate();
            return Inertia::location(route('panel.dashboard'));
        }

        throw ValidationException::withMessages([
            "email" => 'Username or Password not match!'
        ]);
    }
}
