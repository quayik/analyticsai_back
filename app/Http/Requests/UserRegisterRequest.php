<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'website' => 'string|unique:websites,name',
            'website_category' => [
                'required',
                Rule::in([
                    'e-commerce',
                    'landing',
                    'blog'
                ])
            ],
            'email' => 'email|unique:users,email',
            'password' => 'required|string|min:6'
        ];
    }
}
