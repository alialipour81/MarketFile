<?php

namespace App\Http\Requests\dashboard\Discount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestDiscount extends FormRequest
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
            'name'=>['required'],
            'parcent_kasr'=>['required','integer','max:100','min:1'],
            'access'=>['required','numeric'],
            'count_use'=>['required','numeric'],
            'description'=>['required'],
            'dateTime'=>['required','date_format:Y/m/d H:i:s'],
            'status'=>['required','numeric']
        ];
    }
}
