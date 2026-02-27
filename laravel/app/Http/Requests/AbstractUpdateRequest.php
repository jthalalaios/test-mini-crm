<?php

namespace App\Http\Requests;

abstract class AbstractUpdateRequest extends BaseValidationRequest
{
    public function rules(): array
    {
        $rules = $this->get_base_rules();

        foreach ($rules as $field => $rule) {
            $rules[$field][] = 'sometimes';
        }

        return $rules;
    }
}