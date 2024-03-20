<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\ZohoCRM;

class RegisterController extends Controller
{
    protected $redirectTo = "/dashboard";

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        Log::info('Showing registration form');

        // Retrieve user data from the session
        $userData = session('user_data');

        // Show the registration form with the user data
        return view('auth.register', compact('userData'));
    }

    public function redirectToZoho()
    {
        Log::info('Redirecting to Zoho for authentication');

        $zoho = new ZohoCRM();
        return $zoho->redirectToZoho();
    }

    public function handleZohoCallback(Request $request)
    {
        try {
            $zoho = new ZohoCRM();
            $response = $zoho->handleZohoCallback($request);

            if (!$response->successful()) {
                Log::error('OAuth authentication failed', ['response' => $response->body()]);
                return redirect('/register')->withErrors(['oauth' => 'OAuth authentication failed.']);
            }
            Log::info('OAuth authentication successful');
            $tokenData = $response->json();
            Log::info("---------------[Token Data]----------------------------------");
            Log::info("Token Data: ");
            Log::info(print_r($tokenData, true));
            Log::info("---------------[/Token Data]----------------------------------");

            $zoho->access_token = $tokenData['access_token'];
            $zoho->refresh_token = $tokenData['refresh_token'];

            try {
                $userDataResponse = $zoho->getUserData();

                if (!$userDataResponse->successful()) {
                    Log::error('OAuth authentication failed', ['response' => $userDataResponse->body()]);
                    return redirect('/register')->withErrors(['oauth' => 'Failed to retrieve user data from Zoho.']);
                }

                $userData = $userDataResponse->json();
                Log::info("User data: " . print_r($userData, true));

                if (!isset($userData['users'], $userData['users'][0], $userData['users'][0]['id'])) {
                    Log::error('User data not found in response');
                    return redirect('/register')->withErrors(['oauth' => 'User data not found in response.']);
                } else {
                    Log::info("User data found in response!");
                    $userData = $userData['users'][0];
                }

                Log::Info("User Data Response: " . print_r($userDataResponse->json(), true));

                $criteria = "((Last_Name:equals:\(CHR\))and(Email:equals:{$userData['email']}))";
                $fields = "Id,Email,First_Name,Last_Name";
                $contactDataResponse = $zoho->getContactData($criteria, $fields);

                if (!$contactDataResponse->successful()) {
                    Log::error('OAuth authentication failed', ['response' => $contactDataResponse->body()]);
                    return redirect('/register')->withErrors(['oauth' => 'Failed to retrieve contact data from Zoho.']);
                }

                $cdrData = $contactDataResponse->json();
                Log::Info("Contact Data Response: " . print_r($cdrData, true));

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
                session(['user_data' => $userData]);
                session(['token_data' => $tokenData]);
                session(['contact_id' => $contactId]);
                session(['root_user_id' => $rootUserId]);

                // Redirect to registration form
                return redirect()->route('register');
            } catch (\Exception $ex) {
                Log::error("Zoho oauth user process failed: " . $ex->getMessage());
                return redirect('/register')->withErrors(['oauth' => 'Error during OAuth user process: ' . $ex->getMessage()]);
            }

        } catch (\Exception $e) {
            Log::error("Zoho oauth token process failed: " . $e->getMessage());
            return redirect('/register')->withErrors(['oauth' => 'Error during OAuth token process: ' . $e->getMessage()]);
        }
    }

    // Override the register method (can be empty if all logic is handled in handleZohoCallback)
    public function register(Request $request)
    {
        Log::info('Registering user...');

        // Validate the request
        $validatedData = $request->validate([
            'password' => 'required|string|confirmed',
            'name' => 'required|string',
        ]);

        if (!session('user_data') || !session('token_data') || !session('contact_id') || !session('root_user_id')) {
            Log::error('User data not found in session');
            return redirect('/login')->withErrors(['oauth' => 'User data not found in session.']);
        }

        // Retrieve user data from the session
        $userData = session('user_data');
        Log::info("User data: " . print_r($userData, true));

        $tokenData = session('token_data');
        Log::info("Token data: " . print_r($tokenData, true));

        $contactId = session('contact_id');
        Log::info("Contact id: " . print_r($contactId, true));

        $rootUserId = session('root_user_id');
        Log::info("Root user id: " . print_r($rootUserId, true));

        // Encrypt the email, access token, and refresh token
        // Hash the email instead of encrypting
        $hashedEmail = Hash::make($userData['email']);

        $encryptedAccessToken = Crypt::encryptString($tokenData['access_token']);
        $encryptedRefreshToken = Crypt::encryptString($tokenData['refresh_token']);

        // Create or update the user in the database
        $constraint = ['zoho_id' => $contactId];
        $userDBData = [
            'email' => $hashedEmail, // Store hashed email
            'name' => $validatedData['name'],
            'password' => Hash::make($validatedData['password']),
            'access_token' =>  $encryptedAccessToken,
            'refresh_token' => $encryptedRefreshToken,
            'token_expires_at' => now()->addSeconds($tokenData['expires_in']),
            'root_user_id' => $rootUserId,
        ];

        Log::info("Constraint: " . print_r($constraint, true));
        Log::info("User DB data: " . print_r($userDBData, true));

        $user = User::updateOrCreate(
            $constraint,
            $userDBData
        );

        Auth::login($user);

        Log::info('User registered and logged in.');

        return redirect($this->redirectTo);
    }
}
