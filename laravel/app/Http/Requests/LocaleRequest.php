<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'locale' => 'required|string|exists:languages,locale',
        ]; 
    }

    public function messages(): array
    {
        return [
            'locale.required' => __('messages.locale') . ' ' . __('messages.required'),
            'locale.in' => __('messages.locale') . ' ' . __('messages.invalid'),
        ];
    }
}