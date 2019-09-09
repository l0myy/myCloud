<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Session\CacheBasedSessionHandler;
use Session;
use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    protected function redirectTo(){

        return url('/',auth()->user()->id);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('guest')->except('logout');
       #$this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }
}
