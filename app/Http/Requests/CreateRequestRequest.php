<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequestRequest extends FormRequest
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
            // Başlık ve Açıklama
            'title' => 'required|string|max:200',
            'description' => 'required|string|min:50',

            // Karakterler (Opsiyonel)
            'characters' => 'nullable|array|max:5',
            'characters.*.name' => 'required|string|max:100',
            'characters.*.images' => 'required|array|min:5|max:10',
            'characters.*.images.*' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB = 5120KB
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
            // Başlık
            'title.required' => 'Film başlığı zorunludur.',
            'title.max' => 'Film başlığı en fazla 200 karakter olabilir.',

            // Açıklama
            'description.required' => 'Film açıklaması zorunludur.',
            'description.min' => 'Film açıklaması en az 50 karakter olmalıdır.',

            // Karakterler
            'characters.max' => 'En fazla 5 karakter ekleyebilirsiniz.',
            'characters.*.name.required' => 'Karakter adı zorunludur.',
            'characters.*.name.max' => 'Karakter adı en fazla 100 karakter olabilir.',

            // Görseller
            'characters.*.images.required' => 'Her karakter için görsel yüklemelisiniz.',
            'characters.*.images.min' => 'Her karakter için en az 5 görsel yüklemelisiniz.',
            'characters.*.images.max' => 'Her karakter için en fazla 10 görsel yükleyebilirsiniz.',
            'characters.*.images.*.required' => 'Görsel zorunludur.',
            'characters.*.images.*.image' => 'Dosya bir görsel olmalıdır.',
            'characters.*.images.*.mimes' => 'Görsel formatı jpeg, jpg veya png olmalıdır.',
            'characters.*.images.*.max' => 'Her görsel en fazla 5MB olabilir.',
        ];
    }
}
