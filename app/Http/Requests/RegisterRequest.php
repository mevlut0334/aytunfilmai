<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Herkes kayıt olabilir
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:15', 'regex:/^[0-9+]+$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Zorunlu onaylar
            'terms_accepted' => ['required', 'accepted'],
            'copyright_accepted' => ['required', 'accepted'],
            'kvkk_accepted' => ['required', 'accepted'],
            'personal_data_accepted' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom error messages for validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ad Soyad alanı zorunludur.',
            'name.max' => 'Ad Soyad en fazla 255 karakter olabilir.',

            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılmaktadır.',

            'phone.required' => 'Telefon numarası zorunludur.',
            'phone.regex' => 'Telefon numarası sadece rakam ve + işareti içerebilir.',
            'phone.unique' => 'Bu telefon numarası zaten kullanılmaktadır.',
            'phone.max' => 'Telefon numarası en fazla 15 karakter olabilir.',

            'password.required' => 'Şifre zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',

            'terms_accepted.required' => 'Kullanım koşullarını kabul etmelisiniz.',
            'terms_accepted.accepted' => 'Kullanım koşullarını kabul etmelisiniz.',

            'copyright_accepted.required' => 'Telif hakları beyanını kabul etmelisiniz.',
            'copyright_accepted.accepted' => 'Telif hakları beyanını kabul etmelisiniz.',

            'kvkk_accepted.required' => 'KVKK aydınlatma metnini kabul etmelisiniz.',
            'kvkk_accepted.accepted' => 'KVKK aydınlatma metnini kabul etmelisiniz.',

            'personal_data_accepted.required' => 'Kişisel verilerin işlenmesi onayını vermelisiniz.',
            'personal_data_accepted.accepted' => 'Kişisel verilerin işlenmesi onayını vermelisiniz.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'password' => 'Şifre',
            'terms_accepted' => 'Kullanım Koşulları',
            'copyright_accepted' => 'Telif Hakları Beyanı',
            'kvkk_accepted' => 'KVKK Aydınlatma Metni',
            'personal_data_accepted' => 'Kişisel Verilerin İşlenmesi',
        ];
    }
}
