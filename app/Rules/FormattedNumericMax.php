<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FormattedNumericMax implements Rule
{

    public function __construct($maxValue)
    {
        $this->maxValue = $maxValue;
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
        $formatValue = \helper::number_formats($value, 'db', 0);
        
        return strlen($formatValue) <= $this->maxValue;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':Attribute maksimal terdiri dari '. $this->maxValue .' angka.';
    }
}