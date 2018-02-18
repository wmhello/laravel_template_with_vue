<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telphone implements Rule
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
        //
        $reg = '/^0?(13|14|15|17|18|19)[0-9]{9}$/';
        return preg_match($reg, $value)?true:false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 必须是电话号码';
    }
}
