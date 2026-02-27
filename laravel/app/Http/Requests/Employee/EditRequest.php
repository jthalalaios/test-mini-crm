<?php

namespace App\Http\Requests\Employee;

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
        $employeeId = $this->route('employee')->id ?? null;

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'phone' => [
                'nullable',
                'digits:10',
                Rule::unique('employees', 'phone')->ignore($employeeId),
            ],
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('messages.first_name') . ' ' . __('messages.required'),
            'last_name.required' => __('messages.last_name') . ' ' . __('messages.required'),
            'email.required' => __('messages.email') . ' ' . __('messages.required'),
            'email.email' => __('messages.email') . ' ' . __('messages.invalid'),
            'email.unique' => __('messages.email') . ' ' . __('messages.unique'),
            'phone.digits' => __('messages.phone') . ' ' . __('messages.invalid'),
            'phone.unique' => __('messages.phone') . ' ' . __('messages.unique'),
            'company_id.required' => __('messages.company') . ' ' . __('messages.required'),
            'company_id.exists' => __('messages.company') . ' ' . __('messages.invalid'),
        ];
    }
}