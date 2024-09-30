<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpecialCharacters implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the password contains special characters
        return !preg_match('/[^A-Za-z0-9]/', $value);
    }

    public function message()
    {
        return 'The :attribute may not contain special characters.';
    }
}

