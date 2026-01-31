<?php
// app/Services/FileUploadService.php

namespace App\Services;

use App\Enums\Status;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Sube un solo archivo.
     *
     * @param UploadedFile $file
     * @param string $path
     * @return File
     */
    public function uploadSingleFile(UploadedFile $file, string $path = 'general'): File
    {
        $baseDir = 'uploads';
        $folder  = trim($baseDir . '/' . $path, '/');
        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        // putFileAs recibe el directorio, el archivo y el nombre deseado del archivo
        // storage/app/public/uploads/...
        $storagePath = Storage::disk('public')->putFileAs($folder, $file, $filename);

        return File::create([
            'path'        => $storagePath,
            'name'        => $file->getClientOriginalName(),
            'mime_type'   => $file->getClientMimeType(),
            'uploaded_by' => Auth::id(),
            'status'      => Status::ACTIVE,
        ]);
    }

    /**
     * Sube múltiples archivos.
     *
     * @param UploadedFile[] $files
     * @param string $path
     * @return Collection<File>
     */
    public function uploadMultipleFiles(array $files, string $path = 'general'): Collection
    {
        return collect($files)->map(function ($file) use ($path) {
            return $this->uploadSingleFile($file, $path);
        });
    }
}
