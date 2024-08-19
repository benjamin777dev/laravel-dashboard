<?php

namespace App\Http\Controllers;

use App\Services\ZohoCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $zoho;

    public function __construct(ZohoCRM $zoho)
    {
        $this->zoho = $zoho;
    }

    /**
     * Show the user profile edit form.
     */
    public function edit()
    {
        return view('contacts-profile');
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }

        $request->merge([
            'transaction_status_reports' => $request->has('transaction_status_reports') ? true : false,
        ]);

        // Validate the incoming request data
        $request->validate([
            'mobile' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'transaction_status_reports' => 'required|boolean',
            'verified_sender_email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // Log request data to ensure it's being passed correctly
        Log::info('Request data for profile update:', $request->all());

        // Attempt to update the user's profile
        $updated = $user->update([
            'mobile' => $request->input('mobile'),
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
            'street' => $request->input('street'),
            'transaction_status_reports' => $request->input('transaction_status_reports'),
            'verified_sender_email' => $request->input('verified_sender_email'),
        ]);

        // Log whether the update was successful
        Log::info('User profile update status:', ['updated' => $updated]);

        if ($updated) {
            try {
                $this->zoho->access_token = $user->getAccessToken();
                $zohoData = [
                    'data' => [
                        [
                            'Mobile' => $request->input('mobile'),
                            'Mailing_Street' => $request->input('street'),
                            'Mailing_City' => $request->input('city'),
                            'Mailing_State' => $request->input('state'),
                            'Mailing_Zip' => $request->input('zip'),
                            'Verified_Marketing_Sender_Email' => $request->input('verified_sender_email'),
                        ],
                    ],
                    'skip_mandatory' => true,
                ];

                $zohoResponse = $this->zoho->createContactData($zohoData, $user->zoho_id);

                if (!$zohoResponse->successful()) {
                    Log::error('Zoho update failed:', ['response' => $zohoResponse->body()]);
                    return response()->json(['isSuccess' => false, 'Message' => 'Profile updated locally, but failed to update Zoho!']);
                }

            } catch (\Exception $e) {
                Log::error('Exception while updating Zoho:', ['error' => $e->getMessage()]);
                return response()->json(['isSuccess' => false, 'Message' => 'Profile updated locally, but failed to update Zoho due to an exception!']);
            }
        }

        Log::info('User data after update:', $user->toArray());

        return response()->json(['isSuccess' => $updated, 'Message' => $updated ? 'Profile updated successfully!' : 'Profile update failed.']);
    }

    public function updateAgentInfo(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return redirect('/login');
        }
    
        $contact = $user->contact;
        if (!$contact) {
            return response()->json(['isSuccess' => false, 'Message' => 'No contact record found, unable to update contact info!']);
        }
    

        Log::info("request: ", ['request'=>$request]);

        // Boolean fields that might be in the request
        $booleanFields = [
            'tm_preference', 'need_o_e', 'include_insights_in_intro', 'sign_install', 
            'outsourced_mktg_3d_zillow_tour', 'outsourced_mktg_floorplans', 'outsourced_mktg_onsite_video',
            'property_website_qr_code', 'social_media_images', 'social_media_ads', 'feature_cards_or_sheets',
            'print_qr_code_sheet', 'qr_code_sign_rider', 'mls_recolorado', 'mls_ppar', 'mls_ires', 'mls_navica',
            'email_blast_opt_in', 'notepad_mailer_opt_in', 'market_mailer_opt_in', 'review_generation'
        ];
    
        // Merge boolean fields that are present in the request
        foreach ($booleanFields as $field) {
            Log::info("checking if request has field: $field");
            if ($request->has($field)) {
                Log::info("--request has field: $field");
                $request->merge([$field => true]);
            }
        }
    
        // Fields that can be validated
        $fieldsToValidate = array_filter([
            'income_goal' => $request->has('income_goal') ? 'nullable|numeric|max:99999999.99' : null,
            'initial_cap' => $request->has('initial_cap') ? 'nullable|numeric|max:99999999.99' : null,
            'residual_cap' => $request->has('residual_cap') ? 'nullable|numeric|max:99999999.99' : null,
            'agent_name_on_marketing' => $request->has('agent_name_on_marketing') ? 'nullable|string|max:255' : null,
            'additional_email_for_confirmation' => $request->has('additional_email_for_confirmation') ? 'nullable|email|max:255' : null,
            'email_to_cc_on_all_marketing_comms' => $request->has('email_to_cc_on_all_marketing_comms') ? 'nullable|email|max:255' : null,
            'sign_vendor' => $request->has('sign_vendor') ? 'nullable|string|max:255' : null,
            'draft_showing_instructions' => $request->has('draft_showing_instructions') ? 'nullable|string|max:255' : null,
            'title_company' => $request->has('title_company') ? 'nullable|string|max:255' : null,
            'closer_name_phone' => $request->has('closer_name_phone') ? 'nullable|string|max:255' : null,
            'fees_charged_to_seller_at_closing' => $request->has('fees_charged_to_seller_at_closing') ? 'nullable|numeric|max:99999999.99' : null,
            'chr_gives_amount' => $request->has('chr_gives_amount') ? 'nullable|numeric|max:99999999.99' : null,
            'google_business_page_url' => $request->has('google_business_page_url') ? 'nullable|url|max:255' : null,
            'lender_company_name' => $request->has('lender_company_name') ? 'nullable|string|max:255' : null,
            'lender_email' => $request->has('lender_email') ? 'nullable|email|max:255' : null,

        ]);

        Log::info('Fields to Validate:', ['fieldsToValidate' => $fieldsToValidate]);
    
        // Validate the present fields only
        $validatedData = $request->validate($fieldsToValidate);
    
        Log::info('Request data for agent update:', $validatedData);
    
        // Update only the fields that were validated and are present in the request
        $updated = $contact->update($validatedData);
    
        if ($updated) {
            try {
                $this->zoho->access_token = $user->getAccessToken();
                $zohoData = [
                    'data' => [
                        array_filter([
                            'Income_Goal' => $request->input('income_goal'),
                            'Initial_Cap' => $request->input('initial_cap'),
                            'Residual_Cap' => $request->input('residual_cap'),
                            'Agent_Name_on_Marketing' => $request->input('agent_name_on_marketing'),
                            'TM_Preference' => $request->input('tm_preference'),
                            'Additional_Email_for_Confirmation' => $request->input('additional_email_for_confirmation'),
                            'Email_to_CC_on_All_Marketing_Comms' => $request->input('email_to_cc_on_all_marketing_comms'),
                            'Need_O_E' => $request->input('need_o_e'),
                            'Include_Insights_in_Intro' => $request->input('include_insights_in_intro'),
                            'Sign_Install' => $request->input('sign_install'),
                            'Sign_Vendor' => $request->input('sign_vendor'),
                            'Draft_Showing_Instructions' => $request->input('draft_showing_instructions'),
                            'Title_Company' => $request->input('title_company'),
                            'Closer_Name_Phone' => $request->input('closer_name_phone'),
                            'MLS_ReColorado' => $request->input('mls_recolorado'),
                            'MLS_PPAR' => $request->input('mls_ppar'),
                            'MLS_IRES' => $request->input('mls_ires'),
                            'MLS_Navica' => $request->input('mls_navica'),
                            'Fees_Charged_to_Seller_at_Closing' => $request->input('fees_charged_to_seller_at_closing'),
                            'CHR_Gives_Amount' => $request->input('chr_gives_amount'),
                            'Outsourced_Mktg_3D_Zillow_Tour' => $request->input('outsourced_mktg_3d_zillow_tour'),
                            'Outsourced_Mktg_Floorplans' => $request->input('outsourced_mktg_floorplans'),
                            'Outsourced_Mktg_Onsite_Video' => $request->input('outsourced_mktg_onsite_video'),
                            'Property_Website_QR_Code' => $request->input('property_website_qr_code'),
                            'Social_Media_Images' => $request->input('social_media_images'),
                            'Social_Media_Ads' => $request->input('social_media_ads'),
                            'Feature_Cards_or_Sheets' => $request->input('feature_cards_or_sheets'),
                            'Select_your_prints' => $request->input('select_your_prints'),
                            'Print_QR_Code_Sheet' => $request->input('print_qr_code_sheet'),
                            'QR_Code_Sign_Rider' => $request->input('qr_code_sign_rider'),
                            'Email_Blast_Opt_In' => $request->input('email_blast_opt_in'),
                            'Notepad_Mailer_Opt_In' => $request->input('notepad_mailer_opt_in'),
                            'Market_Mailer_Opt_In' => $request->input('market_mailer_opt_in'),
                            'Review_Generation' => $request->input('review_generation'),
                            'Google_Business_Page_URL' => $request->input('google_business_page_url'),
                            'Lender_Company_Name' => $request->input('lender_company_name'),
                            'Lender_Email' => $request->input('lender_email')
                        ], function ($value) {
                            return $value !== null; // Only include fields that have non-null values
                        }),
                    ],
                    'skip_mandatory' => true,
                ];

                Log::info("Zoho Data: ", ['zohoData' => $zohoData]);
    
                $zohoResponse = $this->zoho->createContactData($zohoData, $contact->zoho_contact_id);
    
                if (!$zohoResponse->successful()) {
                    Log::error('Zoho update failed:', ['response' => $zohoResponse->body()]);
                    return response()->json(['isSuccess' => false, 'Message' => 'Agent info updated locally, but failed to update Zoho!']);
                }
    
            } catch (\Exception $e) {
                Log::error('Exception while updating Zoho:', ['error' => $e->getMessage()]);
                return response()->json(['isSuccess' => false, 'Message' => 'Agent info updated locally, but failed to update Zoho due to an exception!']);
            }
        }
    
        Log::info('Agent info update status:', ['updated' => $updated]);
    
        return response()->json(['isSuccess' => $updated, 'Message' => $updated ? 'Agent info updated successfully!' : 'Agent info update failed.']);
    }
    



    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check if the current password is correct
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json([
                'isSuccess' => false,
                'Message' => 'Current password does not match.',
            ]);
        }

        // Update the password
        $this->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'isSuccess' => true,
            'Message' => 'Password changed successfully.',
        ]);
    }

}
