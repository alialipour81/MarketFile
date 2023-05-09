<?php

namespace App\Http\Requests\dashboard\Checkout;

use App\Rules\cardnumber_checkout;
use App\Rules\price_checkout;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestCheckout extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>['required','string'],
            'cardnumber'=>['required','numeric',new cardnumber_checkout()],
            'price'=>['required','numeric'],
        ];
    }
}
