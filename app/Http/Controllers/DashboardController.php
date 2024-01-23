<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token

        // Set default goal or use user-defined goal
        $goal = $user->goal ?? 250000;
        Log::info("Goal: $goal");

        // Retrieve deals from Zoho CRM
        $deals = $this->retrieveDealsFromZoho($user, $accessToken);
        Log::info("Deals: ". print_r($deals, true));
        // Calculate the progress towards the goal
        $progress = $this->calculateProgress($deals, $goal);
        $progressClass = $progress <= 15 ? "bg-danger" : ($progress <= 45? "bg-warning" : "bg-success");
        $progressTextColor = $progress <= 15 ? "#fff" : ($progress <= 45? "#333" : "#fff");
        Log::info("Progress: $progress");
        // Pass data to the view
        return view('dashboard.index', compact('deals', 'progress', 'goal', 'progressClass', 'progressTextColor'));
    }

    private function retrieveDealsFromZoho(User $user, $accessToken)
    {
        $allDeals = collect();
        $page = 1;
        $hasMorePages = true;

        while ($hasMorePages) {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get('https://www.zohoapis.com/crm/v2/Deals', [
                'page' => $page,
                'criteria' => "(Owner:equals:$user->zoho_id) and (Stage:does_not_contain:Cancelled)",
            ]);

            if (!$response->successful()) {
                Log::error("Error retrieving deals: ". $response->body());
                // Handle unsuccessful response
                $hasMorePages = false;
                break;
            }

            Log::info("Successful deal fetch...");
            $responseData = $response->json();
            Log::info("Response data: ". print_r($responseData, true));
            $deals = collect($responseData['data'] ?? []);
            $allDeals = $allDeals->concat($deals);

            $hasMorePages = isset($responseData['info']['more_records']) && $responseData['info']['more_records'];
            $page++;
        }

        return $allDeals;
    }

    private function calculateProgress($deals, $goal)
    {
        $totalGCI = $deals->sum('Pipeline1'); 
        Log::info("Total GCI: $totalGCI");
        $progress = ($totalGCI / $goal) * 100;
        Log::info("Progress: $progress");
        return min($progress, 100); 
    }
}
