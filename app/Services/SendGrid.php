<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Client\RequestException;
use App\Services\DatabaseService;

class SendGrid
{
    private $sendGridApi = 'https://api.sendgrid.com/v3/';

    public function __construct()
    {
        Log::info('Initializing SendGrid');

        $this->sendgrid_api_key = config('services.sendgrid.api_key');
       

        Log::info('SendGrid initialized');
    }

    public function sendgridSenderList()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->sendgrid_api_key,
                'Content-Type' => 'application/json',
            ])->get($this->sendGridApi."senders");

            $responseData = $response->json();

            if (!$response->successful()) {
                Log::error('Sender List Error Response', ['response' => $responseData]);
                throw new \Exception('Failed to get sender list');
            }

            // Log::info('Get sender list response', ['response' => $responseData]);

            return $responseData;
        } catch (\Throwable $th) {
            Log::error('Error Get Sender List: ' . $th->getMessage());
            throw new \Exception('Get Sender List');
        }
    }

    public function verifySender($senderEmail)
    {
        try {
            $sendGridUsers = $this->sendgridSenderList();
            $verified = false;
            // Log::info('Sender Users List', ['sendGridUsers' => $sendGridUsers]);
            foreach ($sendGridUsers as $sender) {
                if ($sender['from']['email'] == $senderEmail) {
                    $verified = $sender['verified']['status'];
                    break;
                }
            }
            return $verified;
        } catch (\Throwable $th) {
            Log::error('Error Sending Email: ' . $th->getMessage());
            throw new \Exception('Failed to Send email');
        }
    }

    public function sendSendGridEmail($inputEmail)
    {
        try {
            $inputEmailJSON = json_encode($inputEmail);
            Log::info("Input Sendgrid Email".$inputEmailJSON);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->sendgrid_api_key,
                'Content-Type' => 'application/json',
            ])->post($this->sendGridApi."mail/send",$inputEmail);
            Log::info('Raw Response', ['response' => $response]);

            $responseData = $response->json();
            if (!$response->successful()) {
                Log::error('Send Email Error Response', ['response' => $responseData]);
                throw new \Exception('Failed to send Email');
            }
            Log::info('Send Email repsonse', ['response' => $responseData]);
            return $responseData;
        } catch (\Throwable $th) {
            Log::error('Error Sending Email: ' . $th->getMessage());
            throw new \Exception('Failed to Send email');
        }
    }
}