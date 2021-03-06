<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // Kada se korisnik uloguje, proverava se da li ima nesto u
        // korpi u sesiji i ako ima onda se merguje sa korpom u bazi
        if($request->session()->has('shoppingCart')) {
            $cart = $request->session()->get('shoppingCart');
            $user->shoppingCart->mergeWithSessionCart($cart);
            $request->session()->forget('shoppingCart'); // izbrise se podatak iz sesije
        }
    }
}
