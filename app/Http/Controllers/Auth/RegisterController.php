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
use Illuminate\Support\Str;

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

        $query = http_build_query([
            'client_id' => env('ZOHO_CLIENT_ID'),
            'redirect_uri' => route('auth.callback'),
            'response_type' => 'code',
            'scope' => 'ZohoProjects.projects.ALL,ZohoCRM.modules.ALL,ZohoCRM.users.ALL,ZohoCRM.settings.ALL,ZohoCRM.org.ALL,ZohoCRM.bulk.READ,ZohoCRM.notifications.READ,ZohoCRM.notifications.CREATE,ZohoCRM.notifications.UPDATE,ZohoCRM.notifications.DELETE,ZohoCRM.coql.READ',
            'prompt' => 'consent',
            'access_type' => 'offline',
        ]);
        Log::info(print_r($query, true));
        Log::info('Zoho authentication URL: https://accounts.zoho.com/oauth/v2/auth?' . $query);

        return redirect('https://accounts.zoho.com/oauth/v2/auth?' . $query);
    }

    public function handleZohoCallback(Request $request)
    {
        Log::info('Handling Zoho callback');
        $headers = [
            'grant_type' => 'authorization_code',
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri' => route('auth.callback'),
            'code' => $request->code,
            'access_type' => 'offline',
        ];

        Log::info('Zoho token URL: https://accounts.zoho.com/oauth/v2/token');
        Log::info(print_r($headers, true));

        try {
            $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', $headers);

            if (!$response->successful()) {
                Log::error('OAuth authentication failed', ['response' => $response->body()]);
                return redirect('/login')->withErrors(['oauth' => 'OAuth authentication failed.']);
            }
            Log::info('OAuth authentication successful');
            $tokenData = $response->json();
            Log::info("---------------[Token Data]----------------------------------");
            Log::info("Token Data: ");
            Log::info(print_r($tokenData, true));
            Log::info("---------------[/Token Data]----------------------------------");

            try {
                $userDataResponse = Http::withHeaders([
                    'Authorization' => 'Zoho-oauthtoken ' . $tokenData['access_token'],
                ])->get('https://www.zohoapis.com/crm/v2/users?type=CurrentUser');

                if (!$userDataResponse->successful()) {
                    Log::error('OAuth authentication failed', ['response' => $userDataResponse->body()]);
                    return redirect('/login')->withErrors(['oauth' => 'Failed to retrieve user data from Zoho.']);
                }

                $userData = $userDataResponse->json()['users'][0];
                Log::info("User data: ". print_r($userData, true));

                // Store user data in the session
                session(['user_data' => $userData]);
                session(['token_data' => $tokenData]);

                // Redirect to registration form
                return redirect()->route('register');
            } catch (\Exception $ex) {
                Log::error("Zoho oauth user process failed: " . $ex->getMessage());
                return redirect('/login')->withErrors(['oauth' => 'Error during OAuth user process: ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            Log::error("Zoho oauth token process failed: " . $e->getMessage());
            return redirect('/login')->withErrors(['oauth' => 'Error during OAuth token process: ' . $e->getMessage()]);
        }
    }

    // Override the register method (can be empty if all logic is handled in handleZohoCallback)
    public function register(Request $request)
    {
        Log::info('Registering user...');

        // Validate the request
        $validatedData = $request->validate([
            'password' => 'required|string|confirmed',
        ]);

        // Retrieve user data from the session
        $userData = session('user_data');
        Log::info("User data: ". print_r($userData, true));

        $tokenData = session('token_data');
        Log::info("Token data: ". print_r($tokenData, true));

        // Encrypt the email, access token, and refresh token
        $encryptedEmail = Crypt::encryptString($userData['email']);
        $encryptedAccessToken = Crypt::encryptString($tokenData['access_token']);
        $encryptedRefreshToken = Crypt::encryptString($tokenData['refresh_token']);

        // Create or update the user in the database
        $constraint = ['zoho_id' => $userData['id']];
        $userDBData = [
            'email' => Crypt::encryptString($userData['email']),
            'name' => $userData['first_name'] . ' ' . $userData['last_name'],
            'password' => Hash::make($request->password),
            'access_token' => Crypt::encryptString($tokenData['access_token']),
            'refresh_token' => Crypt::encryptString($tokenData['refresh_token']),
            'token_expires_at' => now()->addSeconds($tokenData['expires_in']),
        ];

        Log::info("Constraint: ". print_r($constraint, true));
        Log::info("User DB data: ". print_r($userDBData, true));
        

        $user = User::updateOrCreate(
            $constraint,
            $userDBData
        );

        Auth::login($user);

        Log::info('User registered and logged in.');

        return redirect($this->redirectTo);
    }
}
