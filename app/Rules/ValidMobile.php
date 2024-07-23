<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMobile implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove special symbols and spaces from the input
        $cleanedValue = preg_replace('/[^0-9]/', '', $value);

        // Check if the cleaned value is a valid mobile number (digits only)
        return preg_match('/^[0-9]+$/', $cleanedValue);
    }

    public function message()
    {
        return 'The :attribute field must be a valid mobile number.';
    }
}
