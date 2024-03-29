<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User; // Import the User model
use App\Models\Deal; // Import the Deal model
use App\Models\Contact; // Import the Deal model

class DB
{
    public function storeDealsIntoDB($dealsData)
    {
        Log::info("Storing Deals Into Database");

        foreach ($dealsData as $deal) {
            $user = User::where('zoho_id', $deal['Contact_Name']['id'])->first();

            if (!$user) {
                // Log an error if the user is not found
                Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
                continue; // Skip to the next deal
            }

            // Update or create the deal
            Deal::updateOrCreate(['zoho_deal_id' => $deal['id']], [
                'personal_transaction' => $deal['Personal_Transaction'],
                'double_ended' => $deal['Double_Ended'],
                'userID' => $user->id, // Use $user->id instead of $user['id']
                'address' => $deal['Address'],
                'representing' => $deal['Representing'],
                'client_name_only' => $deal['Client_Name_Only'],
                'commission' => $deal['Commission'],
                'probable_volume' => $deal['Probable_Volume'],
                'lender_company' => $deal['Lender_Company'],
                'closing_date' => $deal['Closing_Date'],
                'ownership_type' => $deal['Ownership_Type'],
                'needs_new_date2' => $deal['Needs_New_Date2'],
                'deal_name' => $deal['Deal_Name'],
                'tm_preference' => $deal['TM_Preference'],
                'stage' => $deal['Stage'],
                'sale_price' => $deal['Sale_Price'],
                'zoho_deal_id' => $deal['id'],
                'pipeline1' => $deal['Pipeline1'],
                'pipeline_probability' => $deal['Pipeline_Probability'],
                'zoho_deal_createdTime' => $deal['Created_Time'],
                'property_type' => $deal['Property_Type'],
                'city' => $deal['City'],
                'state' => $deal['State'],
                'lender_company_name' => $deal['Lender_Company_Name'],
                'client_name_primary' => $deal['Client_Name_Primary'],
                'lender_name' => $deal['Lender_Name'],
                'potential_gci' => $deal['Potential_GCI'],
                'contractId' => null,
                'contactId' => null
            ]);
        }

        Log::info("Deals stored into database successfully.");
    }

    /**
     * Store contacts into the database.
     *
     * @param  \Illuminate\Support\Collection  $contacts
     * @return void
     */
    public function storeContactsIntoDB($contacts)
    {
       Log::info("Storing Contacts Into Database");

        foreach ($contacts as $contact) {
            // $user = User::where('zoho_id', $deal['Contact_Name']['id'])->first();

            // if (!$user) {
            //     // Log an error if the user is not found
            //     Log::error("User with Zoho ID {$deal['Contact_Name']['id']} not found.");
            //     continue; // Skip to the next deal
            // }

            // Update or create the deal
           Contact::updateOrCreate(['zoho_contact_id' => $contact['id']], [
                "contact_owner" => isset($contact['Contact Owner']) ? $contact['Contact Owner'] : null,
                "email" => isset($contact['Email']) ? $contact['Email'] : null,
                "first_name" => isset($contact['First Name']) ? $contact['First Name'] : null,
                "last_name" => isset($contact['Last Name']) ? $contact['Last Name'] : null,
                "phone" => isset($contact['Phone']) ? $contact['Phone'] : null,
                "created_time" => isset($contact['Created_Time']) ? $contact['Created_Time'] : null,
                "abcd" => isset($contact['ABCD']) ? $contact['ABCD'] : null,
                "mailing_address" => isset($contact['Mailing_Address']) ? $contact['Mailing_Address'] : null,
                "mailing_city" => isset($contact['Mailing_City']) ? $contact['Mailing_City'] : null,
                "mailing_state" => isset($contact['Mailing_State']) ? $contact['Mailing_State'] : null,
                "mailing_zip" => isset($contact['Mailing_Zip']) ? $contact['Mailing_Zip'] : null,
                "zoho_contact_id"=> isset($contact['id']) ? $contact['id'] : null
            ]);

        }

        Log::info("Contacts stored into database successfully.");
    }
}
