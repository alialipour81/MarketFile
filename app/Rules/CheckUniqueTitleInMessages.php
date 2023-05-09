<?php

namespace App\Rules;

use App\Models\Message;
use Illuminate\Contracts\Validation\Rule;

class CheckUniqueTitleInMessages implements Rule
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
        $check = Message::where('title',$value)->where('user_id',auth()->user()->id)->first();
        return  $check == null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'شما قبلا تیکتی با این عنوان ثبت کرده اید';
    }
}
