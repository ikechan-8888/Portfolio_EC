<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class img implements Rule
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
        $data = preg_split("/[.]/", $value);
        $file = array_pop($data);
        if($file === "jpg" || $file === "jpeg" || $file === "png"){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attributeはjpeg,jpg,png形式を選択してください。';
    }
}
