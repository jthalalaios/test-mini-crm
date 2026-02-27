<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    public function store_files(FormRequest $request, array $validation_rules, string $path)
    {
        $validated_data = $request->validate($validation_rules);
        $user_id = auth()->user()->id;
        if (!isset($validated_data['file'])) return false;

        $requested_file = $validated_data['file'];
        if (!Storage::disk('custom')->exists($path)) Storage::disk('custom')->makeDirectory($path);

        // If a file already exists for this foreign_id + path, delete it
        if (isset($validated_data['foreign_id'])) {
            $existingFile = File::where('foreign_id', $validated_data['foreign_id'])
                                ->where('path', $path)
                                ->latest('id')
                                ->first();

            if ($existingFile && !$existingFile->default) {
                if (Storage::disk('custom')->exists($existingFile->file_path)) Storage::disk('custom')->delete($existingFile->file_path);
                $existingFile->forceDelete();
            }
        }

        $file_path = $requested_file->store($path, 'custom');
        $file_name = $requested_file->getClientOriginalName();
        $file_type = $requested_file->getMimeType();
        $file_size = $requested_file->getSize();
        $file_size = FilesHelper::convertFileSize($file_size);

        $resolution = null;
        if (strpos($file_type, 'image') != false) {
            $image_info = getimagesize($requested_file);
            if ($image_info) $resolution = $image_info[0] . 'x' . $image_info[1];
        }

        $file = File::create([
            'file_name'  => $file_name,
            'file_type'  => $file_type,
            'file_size'  => $file_size,
            'file_path'  => $file_path,
            'foreign_id' => $validated_data['foreign_id'] ?? null,
            'path'       => $path,
            'resolution' => $resolution,
            'user_id'    => $user_id
        ]);

        return $file;
    }

}
