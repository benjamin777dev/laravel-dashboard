<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the user profile edit form.
     */
    public function edit()
    {
        // Return the view for editing the profile
        return view('contacts-profile'); // Ensure this path matches your actual view path
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

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

        // Log the current user data after the update
        Log::info('User data after update:', $user->toArray());

        return response()->json(['isSuccess' => $updated, 'Message' => $updated ? 'Profile updated successfully!' : 'Profile update failed.']);
    }

}
