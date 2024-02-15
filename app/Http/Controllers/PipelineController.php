<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PipelineController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $deals = $this->retrieveDealsFromZoho($user->root_user_id, $accessToken);

        return view('pipeline.index', compact('deals'));
    }

    public function getClosedDeals(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        $accessToken = $user->getAccessToken();
        $closedDeals = $this->retrieveClosedDealsFromZoho($user->root_user_id, $accessToken);

        return view('pipeline.closed', compact('closedDeals')); // Assume you'll create a view for closed deals
    }

    private function retrieveClosedDealsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v2/Deals';
        $criteria = "(Owner:equals:$rootUserId)and(Stage:in:Potential,Pre-Active,Active,Under Contract)";

        $params = [
            'page' => 1,
            'per_page' => 200,
            'criteria' => $criteria,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, $params);

            if ($response->successful()) {
                $responseData = $response->json();
                $closedDeals = collect($responseData['data'] ?? []);
                return $closedDeals;
            } else {
                Log::error("Error fetching closed deals: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching closed deals: {$e->getMessage()}");
            return collect();
        }
    }

    private function retrieveDealsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v2/Deals';
        $params = [
            'page' => 1,
            'per_page' => 200,
            // Adjust criteria as needed to fetch the relevant deals
            'criteria' => "((Owner:equals:$rootUserId)and((Stage:equals:Potential)or(Stage:equals:Pre-Active)or(Stage:equals:Active)or(Stage:equals:Under Contract)))",

        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, $params);

            if ($response->successful()) {
                $responseData = $response->json();
                dd($responseData);
                $deals = collect($responseData['data'] ?? []);
                // You might want to transform or enrich the deals data here
                return $deals;
            } else {
                dd(print_r($response, true));
                Log::error("Error fetching deals: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching deals: {$e->getMessage()}");
            return collect();
        }
    }
}
