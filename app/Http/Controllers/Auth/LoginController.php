<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Where to redirect users after login.
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Overriding the credentials method
    protected function credentials(Request $request)
    {
        $user = User::whereRaw('BINARY email = ?', [Crypt::encryptString($request->get('email'))])->first();
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
            $credentials, $request->filled('remember')
        );
    }
}
