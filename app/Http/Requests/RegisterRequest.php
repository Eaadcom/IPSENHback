<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class RegisterRequest extends RequestAbstract
{

    /**
     * Check if user has given any preference at the registration.
     * If missing set default preference.
     *
     */
    protected function prepareForValidation()
    {
        $preference = $this->defaultPreference();

        foreach ($preference as $key => $value) {
            if (is_null($this->request->get($key))) {
                $this->request->add([$key => $value]);
            }
        }
    }

    private function defaultPreference(): array
    {
        return [
            'about_me'          => 'I 💕 Matcher!',
            'interest'          => 'any',
            'age_range_top'     => 100,
            'age_range_bottom'  => 100,
        ];
    }

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
            'middle_name'       => 'string|min:3',
            'last_name'         => 'required|string|min:3',
            'date_of_birth'     => 'required|date',
            'gender'            => 'required|string|in:male,female',
            'about_me'          => 'string|min:10',
            'age_range_top'     => 'integer|min:18',
            'age_range_bottom'  => 'integer|min:18',
            'interest'          => 'string|in:male,female,any',
        ];
    }
}
