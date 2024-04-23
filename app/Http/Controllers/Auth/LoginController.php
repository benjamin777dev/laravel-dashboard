<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\ZohoCRM;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    
    // Where to redirect users after login.
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /* public function login(Request $request)
    {
        
        return redirect($this->redirectTo);
    } */
    // Overriding the credentials method
    protected function credentials(Request $request)
    {
        $hashedInputEmail = $request->get('email');
        $user = User::where('email', $hashedInputEmail)->first(); 
        if ($user) {
            return ['email' => $user->email, 'password' => $request->get('password')];
        }

        return $request->only($this->username(), 'password');
    }

    // Overriding the attemptLogin method
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        return $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );
    
    }

    

    protected function authenticated(Request $request, $user)
    {
        return redirect('/dashboard');
    }
}
