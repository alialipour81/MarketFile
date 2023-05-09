<?php

namespace App\Rules;

use App\Models\Cart;
use App\Models\Checkout;
use Illuminate\Contracts\Validation\Rule;
use App\Http\Controllers\dashboard\CheckoutsController;

class price_checkout implements Rule
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
        $my_daramad = CheckoutsController::myDaramad(auth()->user()->id);
        $price = CheckoutsController::myCheckout(auth()->user()->id);
        return $value <= (array_sum($my_daramad) - array_sum($price)) ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'قیمت درخواستی شما بیش از موجودی شماست  ';
    }
}
