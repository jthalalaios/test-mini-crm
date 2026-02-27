<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
class FileController extends BaseController
{
    public function store_image(FormRequest $request)
    {
        $validation_rules = [
            'file'       => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'id'         => 'sometimes|integer|exists:files,id',
            'path'       => 'required|string|max:255',
            'foreign_id' => 'sometimes|integer',
        ];

        return $this->store_files($request, $validation_rules, $request->input('path'));
    }
}
