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
        $attempt = $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );
    
        if (!$attempt && $this->isNotFoundError($request)) {
            try{
            // If login attempt failed and it's a 404 error, handle Zoho callback
            $validatedData = $request->validate([
                'password' => 'required|string',
                'name' => 'required|string',
            ]);
            $zoho = new ZohoCRM();
            $redirect_request =  $zoho->redirectToZoho();
            $response = $zoho->handleZohoCallback($redirect_request);
            // Check the response status code directly
            if (!($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299)) {
                Log::error('OAuth authentication failed', ['response' => (string) $response->getBody()]);
                return redirect('/register')->withErrors(['oauth' => 'OAuth authentication failed.']);
            }
            Log::info('OAuth authentication successful');
            $tokenData = json_decode((string) $response->getBody(), true);
            Log::info("---------------[Token Data]----------------------------------");
            Log::info("Token Data: ", [$tokenData]);
            Log::info("---------------[/Token Data]----------------------------------");

            $zoho->access_token = $tokenData['access_token'];
            $zoho->refresh_token = $tokenData['refresh_token'];

            $userDataResponse = $zoho->getUserData();
            if (!($userDataResponse->getStatusCode() >= 200 && $userDataResponse->getStatusCode() <= 299)) {
                Log::error('Failed to retrieve user data from Zoho', ['response' => (string) $userDataResponse->getBody()]);
                return redirect('/register')->withErrors(['oauth' => 'Failed to retrieve user data from Zoho.']);
            }

            $userData = json_decode((string) $userDataResponse->getBody(), true);
            Log::info("User data: ", [$userData]);
            if (!isset($userData['users'], $userData['users'][0], $userData['users'][0]['id'])) {
                Log::error('User data not found in response');
                return redirect('/register')->withErrors(['oauth' => 'User data not found in response.']);
            } else {
                Log::info("User data found in response!");
                $userData = $userData['users'][0];
            }
           
            $criteria = "((Last_Name:equals:\(CHR\))and(Email:equals:{$userData['email']}))";
            $fields = "Id,Email,First_Name,Last_Name";
            $contactDataResponse = $zoho->getContactData($criteria, $fields);
            if (!($contactDataResponse->getStatusCode() >= 200 && $contactDataResponse->getStatusCode() <= 299)) {
                Log::error('Failed to retrieve contact data from Zoho', ['response' => (string) $contactDataResponse->getBody()]);
                return redirect('/register')->withErrors(['oauth' => 'Failed to retrieve contact data from Zoho.']);
            }

            $cdrData = json_decode((string) $contactDataResponse->getBody(), true);
            Log::Info("Contact Data Response: ", [$cdrData]);

            if (!isset($cdrData['data'], $cdrData['data'][0], $cdrData['data'][0]['id'])) {
                Log::error('Contact data not found in response');
                return redirect('/register')->withErrors(['oauth' => 'Contact data not found in response.']);
            } else {
                Log::info("Contact data found in response!");
                $cdrData = $cdrData['data'][0];
            }

            $rootUserId = $userData['id'];
            $contactId = $cdrData['id'];
            Log::info("Root User ID: " . $rootUserId);
            Log::Info("Contact ID: " . $contactId);

            // Store user data in the session
            session([
                'user_data' => $userData,
                'token_data' => $tokenData,
                'contact_id' => $contactId,
                'root_user_id' => $rootUserId
            ]);
           

            if (!session('user_data') || !session('token_data') || !session('contact_id') || !session('root_user_id')) {
                Log::error('User data not found in session');
                return redirect('/login')->withErrors(['oauth' => 'User data not found in session.']);
            }

             // Retrieve user data from the session
        $sessionUserData = session('user_data');
        Log::info("User data: " . print_r($sessionUserData, true));

        $sessionTokenData = session('token_data');
        Log::info("Token data: " . print_r($sessionTokenData, true));

        $sessionContactId = session('contact_id');
        Log::info("Contact id: " . print_r($sessionContactId, true));

        $sessionRootUserId = session('root_user_id');
        Log::info("Root user id: " . print_r($sessionRootUserId, true));

        // Encrypt the email, access token, and refresh token
        // Hash the email instead of encrypting
        $hashedEmail = $sessionUserData['email'];

        $encryptedAccessToken = Crypt::encryptString($sessionTokenData['access_token']);
        $encryptedRefreshToken = Crypt::encryptString($sessionTokenData['refresh_token']);

        // Create or update the user in the database
        $constraint = ['zoho_id' => $sessionContactId];
        $userDBData = [
            'email' => $hashedEmail, // Store hashed email
            'name' => $validatedData['name'],
            'password' => Hash::make($validatedData['password']),
            'access_token' =>  $encryptedAccessToken,
            'refresh_token' => $encryptedRefreshToken,
            'token_expires_at' => now()->addSeconds($sessionTokenData['expires_in']),
            'root_user_id' => $sessionRootUserId,
        ];

        Log::info("Constraint: " . print_r($constraint, true));
        Log::info("User DB data: " . print_r($userDBData, true));

        $user = User::updateOrCreate(
            $constraint,
            $userDBData
        );
       
        $userAlreadyExists = User::where("email", $userData["email"])->first();
        if ($userAlreadyExists) {
            Log::info("User data found in response!" . $userAlreadyExists);
            return redirect($this->redirectTo);
        }

        Auth::login($user);

        return redirect($this->redirectTo);
    } catch (\Exception $e) {
        Log::error("Error creating notes:new " . $e->getMessage());
        return redirect()->back()->with('error', '!'.$e->getMessage());
       }
           
        }
    
        return $attempt;
    }
    
    private function isNotFoundError($request)
    {
        // Check if the response status is 404
        return app()->runningUnitTests() || $request->route()->parameter('exception') instanceof NotFoundHttpException;
    }
    

    protected function authenticated(Request $request, $user)
    {
        return redirect('/dashboard');
    }
}
