<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $accessToken = $user->getAccessToken(); // Ensure we have a valid access token

        // Set default goal or use user-defined goal
        $goal = $user->goal ?? 250000;

        // Retrieve deals from Zoho CRM
        $deals = $this->retrieveDealsFromZoho($user, $accessToken);

        // Calculate the progress towards the goal
        $progress = $this->calculateProgress($deals, $goal);

        // Pass data to the view
        return view('dashboard.index', compact('deals', 'progress', 'goal'));
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
                // Handle unsuccessful response
                $hasMorePages = false;
                break;
            }

            $responseData = $response->json();
            $deals = collect($responseData['data'] ?? []);
            $allDeals = $allDeals->concat($deals);

            $hasMorePages = isset($responseData['info']['more_records']) && $responseData['info']['more_records'];
            $page++;
        }

        return $allDeals;
    }

    private function calculateProgress($deals, $goal)
    {
        $totalGCI = $deals->sum('Pipeline1'); // Update this field name based on your Zoho CRM Deal field

        $progress = ($totalGCI / $goal) * 100;
        return min($progress, 100); // To ensure it doesn't exceed 100%
    }
}
