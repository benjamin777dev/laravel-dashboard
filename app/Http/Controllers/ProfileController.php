<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users')->ignore($user->id),
            ],
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
                'max:191',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // Update the user's profile
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
            'street' => $request->input('street'),
            'transaction_status_reports' => $request->input('transaction_status_reports'),
            'verified_sender_email' => $request->input('verified_sender_email'),
        ]);

        return response()->json(['isSuccess' => true, 'Message' => 'Profile updated successfully!']);
    }
}
