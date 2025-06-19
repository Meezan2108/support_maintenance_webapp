<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Middleware to restrict access for guests except logout
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function form()
    {
        return inertia('Auth/Login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember_me = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember_me)) {
            $request->session()->regenerate();

            return redirect()
                ->route('panel.dashboard')
                ->with('success', 'Successfully logged in!');
        }

        // Wrong credentials — redirect back with error flash message
        return redirect()
            ->route('login')            
            ->with('error', 'Invalid email or password.');
    }
}
