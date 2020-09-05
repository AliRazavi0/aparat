<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mobileRegex = '~^(0098|\+?98|0)9\d{9}$~';
        preg_match($mobileRegex, $value, $maches);
        return !empty($maches);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'شماره موبایل وارد شده اشتباه میباشد';
    }
}
