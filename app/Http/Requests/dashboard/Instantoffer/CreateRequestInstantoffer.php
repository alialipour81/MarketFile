<?php

namespace App\Http\Requests\dashboard\Instantoffer;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestInstantoffer extends FormRequest
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
            'product_id'=>['required','integer','unique:instantoffers,product_id']
        ];
    }
}
