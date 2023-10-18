<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Providers\RouteServiceProvider;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function authenticated($request, $user)
    {
        if ($user->isAdmin()) {
            // Pengguna dengan peran admin akan diarahkan ke halaman admin
            return redirect('/home');
        } elseif ($user->isOwner()) {
            // Pengguna dengan peran user akan diarahkan ke halaman user
            return redirect('/home');
        } else {
            // Pengguna dengan peran lain akan diarahkan ke halaman default
            return redirect('/');
        }
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
