<?php

namespace App\Http\Requests\dashboard\Slider;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestSlider extends FormRequest
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
            'title'=>['required','string'],
            'image'=>['required','image','mimes:png,jpeg,jpg','max:1000'],
            'link'=>['required','url']
        ];
    }
}
