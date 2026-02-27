<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:companies,name',
            'email' => 'sometimes|email|unique:companies,email',
            'website' => 'sometimes|url|unique:companies,website',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=100,min_height=100|max:2048',
        ]; 
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.name') . ' ' . __('messages.required'),
            'name.unique' => __('messages.name') . ' ' . __('messages.unique'),
            'email.email' => __('messages.email') . ' ' . __('messages.invalid'),
            'email.unique' => __('messages.email') . ' ' . __('messages.unique'),
            'website.url' => __('messages.website') . ' ' . __('messages.invalid'),
            'website.unique' => __('messages.website') . ' ' . __('messages.unique'),
            'logo.image' => __('messages.logo') . ' ' . __('messages.invalid_image'),
            'logo.mimes' => __('messages.logo') . ' ' . __('messages.invalid_image_format'),
            'logo.dimensions' => __('messages.logo') . ' ' . __('messages.invalid_image_dimensions'),
            'logo.max' => __('messages.logo') . ' ' . __('messages.image_too_large'),
        ];
    }
}