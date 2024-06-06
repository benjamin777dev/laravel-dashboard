<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;

class UpdateFromZohoCRMController extends Controller
{
    public function handleContactUpdate(Request $request)
    {
        $data = $request->all();

        Log::info('Received contact update from Zoho CRM', ['data' => $data]);

        $zohoContactId = $data['id'];


        // Map the data using the Contact model's mapping method
        $mappedData = Contact::mapZohoData($data, 'webhook');

        // Update or create the contact record in the database
        try {
            Contact::updateOrCreate(
                ['zoho_contact_id' => $zohoContactId],
                $mappedData
            );
        } catch (\Exception $e) {
            Log::error('Error updating contact', ['zoho_contact_id' => $zohoContactId, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating contact'], 500);
        }

        Log::info('Contact updated/inserted successfully', ['zoho_contact_id' => $zohoContactId]);

        return response()->json(['message' => 'Contact updated successfully'], 200);
    }
}
