<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class UpdateUserRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => "required|email|unique:users,email," . auth()->id(),
            'password' => 'string|min:8',
            'first_name' => 'string|min:3',
            'middle_name' => 'nullable|string|min:3',
            'last_name' => 'required|string|min:3',
            'date_of_birth' => 'required|date',
            'about_me' => 'nullable|string',
            'age_range_bottom' => 'integer',
            'age_range_top' => 'integer',
            'max_distance' => 'integer',
            'interest' => 'string|in:male,female,any',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}
