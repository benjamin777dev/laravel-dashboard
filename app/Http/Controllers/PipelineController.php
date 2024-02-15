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

    private function retrieveDealsFromZoho($rootUserId, $accessToken)
    {
        $url = 'https://www.zohoapis.com/crm/v2/Deals';
        $params = [
            'page' => 1,
            'per_page' => 200,
            // Adjust criteria as needed to fetch the relevant deals
            'criteria' => "((Owner:equals:$rootUserId)and((Stage:equals:Potential)or(Stage:equals:Pre-Active)or(Stage:equals:Active)or(Stage:equals:Under Contract)))"
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            ])->get($url, $params);

            if ($response->successful()) {
                $responseData = $response->json();
                $deals = collect($responseData['data'] ?? []);
                // You might want to transform or enrich the deals data here
                return $deals;
            } else {
                Log::error("Error fetching deals: {$response->body()}");
                return collect();
            }
        } catch (\Exception $e) {
            Log::error("Exception when fetching deals: {$e->getMessage()}");
            return collect();
        }
    }
}