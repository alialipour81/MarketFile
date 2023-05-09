<?php

namespace App\Http\Requests\dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestProduct extends FormRequest
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
            'title'=>['required','unique:products,title'],
            'image1'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'image2'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'image3'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'image4'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'description'=>['required','string'],
            'attrbutes'=>['required','string'],
        ];
    }
}
