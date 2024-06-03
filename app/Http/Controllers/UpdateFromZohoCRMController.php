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

        // Extract necessary data from the payload
        $zohoContactId = $data['id'];

        // Ensure zoho_contact_id is included in the data array
        $data['zoho_contact_id'] = $zohoContactId;

        // Update or create the contact record in the database
        try {
            Contact::updateOrCreate(
                ['zoho_contact_id' => $zohoContactId],
                $data
            );
        } catch (\Exception $e) {
            Log::error('Error updating contact', ['zoho_contact_id' => $zohoContactId, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating contact'], 500);
        }

        Log::info('Contact updated/inserted successfully', ['zoho_contact_id' => $zohoContactId]);

        return response()->json(['message' => 'Contact updated successfully'], 200);
    }
}
