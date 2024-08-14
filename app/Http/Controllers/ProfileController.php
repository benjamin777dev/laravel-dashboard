<?php

namespace App\Http\Controllers;

use App\Services\ZohoCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('contacts-profile'); // Ensure this path matches your actual view path
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

        $request->validate([
            'income_goal' => 'nullable|string|max:191',
            'initial_cap' => 'nullable|string|max:255',
            'residual_cap' => 'nullable|string|max:255',
        ]);

        Log::info('Request data for agent update:', $request->all());

        $updated = $contact->update([
            'income_goal' => $request->input('income_goal'),
            'initial_cap' => $request->input('initial_cap'),
            'residual_cap' => $request->input('residual_cap'),
        ]);

        if ($updated) {
            try {
                $this->zoho->access_token = $user->getAccessToken();
                $zohoData = [
                    'data' => [
                        [
                            'Income_Goal' => $request->input('income_goal'),
                            'Initial_Cap' => $request->input('initial_cap'),
                            'Residual_Cap' => $request->input('residual_cap'),
                        ],
                    ],
                    'skip_mandatory' => true,
                ];

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
}
