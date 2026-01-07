<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Auth middleware zaten kontrol ediyor
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_id' => 'required|integer|exists:packages,id',
            'quantity' => 'required|integer|min:1|max:99',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'package_id.required' => 'Paket seçimi zorunludur.',
            'package_id.integer' => 'Geçersiz paket.',
            'package_id.exists' => 'Seçilen paket bulunamadı.',
            'quantity.required' => 'Miktar zorunludur.',
            'quantity.integer' => 'Miktar sayı olmalıdır.',
            'quantity.min' => 'Miktar en az 1 olmalıdır.',
            'quantity.max' => 'Miktar en fazla 99 olabilir.',
        ];
    }
}
