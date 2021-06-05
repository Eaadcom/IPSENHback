<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class RegisterRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8',
            'first_name'        => 'required|string|min:3',
            'middle_name'       => 'required|string|min:3',
            'last_name'         => 'required|string|min:3',
            'gender'            => 'required|string',
            'date_of_birth'     => 'required|date',
            'about_me'          => 'required|string',
            'age_range_top'     => 'required|integer',
            'age_range_bottom'  => 'required|integer',
            'max_distance'      => 'required|integer',
            'interest'          => 'required|string',
        ];
    }
}
