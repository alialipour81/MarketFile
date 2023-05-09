<?php

namespace App\Http\Requests\dashboard\Store;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestStore extends FormRequest
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
            'name'=>['required','unique:stores,name'],
            'image'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'description'=>['required']
        ];
    }
}
