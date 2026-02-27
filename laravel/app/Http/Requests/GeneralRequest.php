<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class GeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'filter' => 'array|sometimes',
            'items' => 'string|sometimes',
            'sort' => 'array|sometimes',
        ]; 
    }
}
