<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class LoginRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "email"     => "required|email",
            "password"  => "required|string|min:8"
        ];
    }
}
