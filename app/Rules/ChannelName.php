<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ChannelName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('~^[a-z-A-Z_][a-z-A-Z0-9\-_]{3,254}$~', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Channel name must contain only this chars a-z, A-Z, 0-9, _ or - with min length of 4';
    }
}
