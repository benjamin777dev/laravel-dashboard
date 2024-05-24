<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'zoho_id',
        'root_user_id',
        'goal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAccessToken()
    {
        Log::info('getAccessToken');
        if ($this->isAccessTokenValid()) {
            Log::info('getAccessToken: valid');
           return Crypt::decryptString($this->access_token);
        }

        Log::info('getAccessToken: expired');
        return $this->refreshAccessToken();
    }

    public static function getUsersByname()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('root_user_id', 'name')->get();
    }

    private function isAccessTokenValid()
    {
        Log::info('isAccessTokenValid check');
        return $this->token_expires_at && now()->lt($this->token_expires_at);
    }

    private function refreshAccessToken()
    {
        Log::info('refreshAccessToken check');

        $curl = curl_init();
        $curlParams = [
            'refresh_token' => $this->getDecryptedRefreshToken(),
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'grant_type' => 'refresh_token',
        ];

        Log::info("Params: " . print_r($curlParams, true));

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $curlParams));

        $response = curl_exec($curl);
        Log::info("Response: " . print_r($response, true));
        curl_close($curl);
        $responseBody = json_decode($response);
        Log::info("Response body: " . print_r($responseBody, true));

        if (isset($responseBody->access_token)) {
            Log::info("Response body: " . print_r($responseBody, true));
            $this->access_token = Crypt::encryptString($responseBody->access_token);
            $this->token_expires_at = now()->addSeconds($responseBody->expires_in);
            $this->save();
            Log::info("Access token: " . $this->access_token);
            return $responseBody->access_token;
        } else {
            Log::error("Error refreshing access token: " . $response);
            // redirect back to oauth process
            return redirect('/auth/callback');
        }

        // Handle failed refresh here, like logging and redirecting to OAuth flow
    }

    public function getDecryptedRefreshToken()
    {
        Log::info('getDecryptedRefreshToken check');
        $refreshToken = Crypt::decryptString($this->refresh_token);
        Log::info("Decrypted refresh token: " . $refreshToken);
        return $refreshToken;
    }
}
