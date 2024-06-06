<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class ZohoBulkRead
{
    protected $apiUrl = 'https://www.zohoapis.com/crm/bulk/v6/';
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->user->getAccessToken();
    }

    public function createBulkReadJob($module)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->user->getAccessToken(),
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . 'read', [
                'callback' => [
                    'url' => 'https://zportal.coloradohomerealty.com/api/webhook/csvcallback',
                    'method' => 'post'
                ],
                'query' => [
                    'module' => [
                        'api_name' => $module
                    ]
                ]
            ]);

            if ($response->successful()) {
                Log::info("Bulk read job created successfully for module: {$module}");
                return $response->json();
            } else {
                Log::error("Error creating bulk read job for module: {$module}", ['response' => $response->json()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception creating bulk read job: " . $e->getMessage());
            return null;
        }
    }

    public function checkJobStatus($jobId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->user->getAccessToken()
            ])->get($this->apiUrl . 'read/' . $jobId);

            if ($response->successful()) {
                Log::info("Bulk read job status checked successfully for job ID: {$jobId}");
                return $response->json();
            } else {
                Log::error("Error checking bulk read job status for job ID: {$jobId}", ['response' => $response->json()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception checking bulk read job status: " . $e->getMessage());
            return null;
        }
    }

    public function downloadResult($jobId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->user->getAccessToken()
            ])->get($this->apiUrl . 'read/' . $jobId . '/result');

            if ($response->successful()) {
                Log::info("Bulk read job result downloaded successfully for job ID: {$jobId}");
                return $response->body();
            } else {
                Log::error("Error downloading bulk read job result for job ID: {$jobId}", ['response' => $response->json()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception downloading bulk read job result: " . $e->getMessage());
            return null;
        }
    }
}
