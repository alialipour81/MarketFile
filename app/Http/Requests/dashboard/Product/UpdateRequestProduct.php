<?php

namespace App\Http\Requests\dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestProduct extends FormRequest
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
            'type'=>['required','string'],
            'category_id'=>['required','integer'],
            'store_id'=>['required','integer'],
            'title'=>['required'],
            'description'=>['required','string'],
            'attrbutes'=>['required','string'],
            'status'=>['required','numeric'],
            'price'=>['required','numeric'],
            'new_price'=>['required','numeric'],
        ];
    }
}
