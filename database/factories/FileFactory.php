<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Datos por defecto que NO requieren internet
        return [
            'path'        => 'storage/app/public/uploads/default.jpg',
            'name'        => 'default.jpg',
            'mime_type'   => 'image/jpeg',
            'uploaded_by' => 1,
            'status'      => Status::ACTIVE,
        ];
    }

    public function withPath(string $subDir): static
    {
        return $this->state(function () use ($subDir) {
            $baseDir = 'uploads';
            $folder  = trim($baseDir . '/' . $subDir, '/');
            $filename = time() . '_' . Str::random(8) . '.jpg';

            // creamos una imagen vacía de 100x100 px
            $image = UploadedFile::fake()->image($filename, 100, 100);
            $storagePath =  Storage::disk('public')->putFileAs($folder, $image, $filename);

            return [
                'path'        => $storagePath,
                'name'        => $filename,
            ];
        });
    }
}
