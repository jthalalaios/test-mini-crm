<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $company_id = $this->route('company')->id ?? null;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->ignore($company_id),
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('companies', 'email')->ignore($company_id),
            ],
            'website' => [
                'sometimes',
                'url',
                Rule::unique('companies', 'website')->ignore($company_id),
            ],
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