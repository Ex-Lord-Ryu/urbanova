<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/home';

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
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // If user is admin, redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('home');
        }

        // If user is regular user, check if profile is complete
        if ($user->role === 'user') {
            // Check if profile is complete (has phone, gender, and birth_date)
            if (empty($user->phone) || empty($user->gender) || empty($user->birth_date)) {
                // Profile is incomplete, redirect to profile edit page
                return redirect()->route('landing.profile.edit');
            } else {
                // Profile is complete, redirect to landing page
                return redirect()->route('landing');
            }
        }

        // Default redirect
        return redirect()->intended($this->redirectPath());
    }
}
