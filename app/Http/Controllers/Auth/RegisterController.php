<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MhCustomer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
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
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        $user = new User();

        $user->email = old("email");

        return view(
            "auth.register",
            [
                "action_url" => url("/register"),
                "user" => $user
            ]
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:App\Models\User,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'string'],
        ]);

        if ($request->password != $request->confirm_password) {
            return redirect()->back()->withErrors([
                'password' => 'Password & Confirm Password Not Match',
                'confirm_password' => 'Password & Confirm Password Not Match',
            ])->withInput();
        } else {
            $admin = new User();
            $admin->id_mh_admin_group = 6;
            $admin->reference_table = get_class(new MhCustomer());

            $admin->username = '';
            $admin->name = trim($request->name, " \t\n\r\0\x0B");
            $admin->email = trim($request->email, " \t\n\r\0\x0B");
            $admin->password = password_hash($request->password, PASSWORD_DEFAULT);
            $result1 = $admin->save();
        }

        /**
         * Color Documentation
         * https://materializecss.com/color.html
         *  */
        if ($result1 == true) {
            DB::commit();
            return redirect('/login')->with('message', [
                'title' => 'Success',
                'content' => 'Admin added successfuly',
                'color' => 'cyan',
            ]);
        } else {
            DB::rollBack();
            return redirect()->back()->with('message', [
                'title' => 'Failed',
                'content' => 'Failed to add new admin',
                'color' => 'red',
            ]);
        }
    }
}
