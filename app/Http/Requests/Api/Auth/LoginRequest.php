<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'E-posta adresi zorunludur.',
            'email.email'       => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre zorunludur.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => 'E-posta',
            'password' => 'Şifre',
        ];
    }
}
