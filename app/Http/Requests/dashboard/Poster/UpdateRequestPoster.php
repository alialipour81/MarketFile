<?php

namespace App\Http\Requests\dashboard\Poster;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestPoster extends FormRequest
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
            'link'=>['required','url'],
            'status'=>['required','numeric']
        ];
    }
}
