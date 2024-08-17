<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchoolRequest extends FormRequest
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
            'school_name' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'short_code' => 'required',
            'logo' => 'sometimes',
        ];
    }

    public function messages()
    {
        return [
                'school_name.required' => 'The School Name field is required.',
                'address.required' => 'The Address field is required.',
                'state.required' => 'The state field is required.',
                'city.required' => 'The city field is required.',
                'phone.required' => 'The phone field is required.',
                'email.required' => 'The email field is required.',
                'short_code.required' => 'The short code field is required.',
        ];
    }
}
