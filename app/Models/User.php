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
        'zoho_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'remember_token',
        'created_at',
        'updated_at',
        'goal',
        'root_user_id',
        'country',
        'city',
        'state',
        'zip',
        'street',
        'language',
        'locale',
        'is_online',
        'currency',
        'time_format',
        'profile_name',
        'profile_id',
        'mobile',
        'time_zone',
        'created_time',
        'modified_time',
        'confirmed',
        'full_name',
        'date_format',
        'status',
        'website',
        'email_blast_opt_in',
        'strategy_group',
        'notepad_mailer_opt_in',
        'market_mailer_opt_in',
        'role_name',
        'role_id',
        'modified_by_name',
        'modified_by_id',
        'created_by_name',
        'created_by_id',
        'alias',
        'fax',
        'country_locale',
        'sandbox_developer',
        'microsoft',
        'reporting_to',
        'offset',
        'next_shift',
        'shift_effective_from',
        'transaction_status_reports',
        'joined_date',
        'territories',
        'verified_sender_email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token',
        'refresh_token',
        'token_expires_at'
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
            $decryptAccessToken = Crypt::decryptString($this->access_token);
            return $decryptAccessToken;
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

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $curlParams
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseBody = json_decode($response);

        if (isset($responseBody->access_token)) {
            $this->access_token = Crypt::encryptString($responseBody->access_token);
            $this->token_expires_at = now()->addSeconds($responseBody->expires_in);
            $this->save();
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

    public function contact()
    {
        return $this->hasOne(Contact::class, 'zoho_contact_id', 'zoho_id');
    }

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'zoho_id', 'zoho_contact_id');
    }

    public function isPartOfTeam()
    {
        // Get the contact associated with this user
        $contact = $this->contact;

        if ($contact) {
            // Check if this contact is part of a team/partnership
            return $contact->teamAndPartnership()->exists();
        }

        // Fallback check using TeamAndPartnership model
        return TeamAndPartnership::isTeamAgent($this->id);
    }

    public function callRecord()
    {
        $this->hasMany(CallRecord::class, 'user_id');
    }
}
