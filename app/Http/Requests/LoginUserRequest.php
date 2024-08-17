<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'number' => 'sometimes|required_without:email',
            'email' => 'sometimes|required_without:number',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
                'number.required' => 'The number field is required.',
                'email.required' => 'The email field is required.',
                'password.required' => 'The password field is required.'
        ];
    }


}
