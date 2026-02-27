<?php

namespace App\Helpers;

use App\Models\File;

class FilesHelper
{
    public static function convertFileSize(int $size): string
    {
        if ($size >= 1048576) { // 1024 * 1024 = 1 MB
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) { // 1 KB
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' B';
        }
    }

    public static function getFilesByPath($foreign_id = null, $paths = [])
    {
        $single = !is_array($paths);
        $paths = $single ? [$paths] : $paths;

        $files = File::where('foreign_id', $foreign_id)
            ->whereIn('path', $paths)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('path');

        $result = [];
        foreach ($paths as $path) {
            $path_files = $files->get($path, collect());
            $file = $path_files
                ->first(fn($file) => !$file->default)
                ?? $path_files->first();

            if ($file) {
                $result[$path] = [
                    'file_path' => $file->file_path,
                    'id' => $file->id,
                ];
            }
        }

        return $result;
    }
}
