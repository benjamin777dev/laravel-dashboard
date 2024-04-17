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
    // protected function login(Request $request)
    // {
      
    //     $validate = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    //     // Check if email and password match a user in the database
    //     $user = User::where('email', $validate['email'])->first();
    //     if ($user && Hash::check($validate['password'], $user->password)) {
    //         // Redirect to Zoho for authentication
    //         $zoho = new ZohoCRM();
    //         $redirect_request = $zoho->redirectToZoho();
    //         echo $redirect_request;
    //     } else {
    //         // Email and password do not match, handle the error
    //         // For example, you can return a response indicating authentication failure
    //         return redirect()->back()->with('password', 'credetials not match!');
    //     }
    // }

    protected function authenticated(Request $request, $user)
    {
        return redirect('/dashboard');
    }
}
