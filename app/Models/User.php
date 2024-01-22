<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

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
        'goal'
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
        if ($this->isAccessTokenValid()) {
            return Crypt::decryptString($this->access_token);
        }

        return $this->refreshAccessToken();
    }

    private function isAccessTokenValid()
    {
        return $this->token_expires_at && now()->lt($this->token_expires_at);
    }

    private function refreshAccessToken()
    {
        $response = Http::post('https://accounts.zoho.com/oauth/v2/token', [
            'refresh_token' => $this->getDecryptedRefreshToken(),
            'client_id' => 'your-client-id',
            'client_secret' => 'your-client-secret',
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $responseBody = $response->json();
            $this->access_token = Crypt::encryptString($responseBody['access_token']);
            $this->token_expires_at = now()->addSeconds($responseBody['expires_in']);
            $this->save();

            return $responseBody['access_token'];
        }

        // Handle failed refresh here, like logging and redirecting to OAuth flow
    }

    public function getDecryptedRefreshToken()
    {
        return Crypt::decryptString($this->refresh_token);
    }
}
