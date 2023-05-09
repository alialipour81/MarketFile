<?php

namespace App\Http\Requests\dashboard\Store;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestStore extends FormRequest
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
            'status'=>['required'],
            'bluetick'=>['required'],
            'description'=>['required'],
        ];
    }
}
