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

        $file = $this->store_files($request, $validation_rules, $request->input('path'));

        // Set permissions and ownership for Docker (www-data)
        if ($file && isset($file->file_path)) {
            $fullPath = base_path('storage/app/public/' . ltrim($file->file_path, '/'));
            if (file_exists($fullPath)) {
                @chmod($fullPath, 0644);
                @chown($fullPath, 'www-data');
            }
            // Also set directory permissions
            $dirPath = dirname($fullPath);
            if (is_dir($dirPath)) {
                @chmod($dirPath, 0775);
                @chown($dirPath, 'www-data');
            }
        }
        return $file;
    }
}
