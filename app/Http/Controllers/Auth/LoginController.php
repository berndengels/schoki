<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle an authentication attempt.
     * @param Request $request
     * @return Response
     */
    public function authenticate( Request $request )
    {
        if (Auth::attempt([
            'username'  => $request->username,
            'password'  => $request->password,
            'enabled'   => 1,
        ], $request->remember)) {
            return redirect()->intended('admin');
        }
    }

    function authenticated(Request $request, $user)
    {
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
    }

    public function username()
    {
        return 'username';
    }

    public function logout()
    {
        Auth::logout();
		return redirect()->route('events');
	}

    protected function guard()
    {
        return Auth::guard('web');
    }
}
