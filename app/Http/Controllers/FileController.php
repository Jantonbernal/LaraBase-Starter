<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FilesRequest;
use App\Http\Resources\FileResource;
use App\Services\FileUploadService;
use App\Traits\Loggable;
use Illuminate\Support\Facades\DB;
use Throwable;

class FileController extends Controller
{
    use Loggable;

    public function uploadImage(FileRequest $request)
    {
        DB::beginTransaction();

        try {
            $service = resolve(FileUploadService::class);

            $subDir = $request->input('path', 'images');

            $uploadedFile = $service->uploadSingleFile($request->file('file'), $subDir);

            DB::commit();
            return response()->json([
                'success' => true,
                'file'    => new FileResource($uploadedFile),
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Fallo al subir imagen', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function uploadFiles(FilesRequest $request)
    {
        DB::beginTransaction();

        try {
            if ($request->hasFile('files')) {
                $service = resolve(FileUploadService::class);

                $subDir = $request->input('path', 'general');

                $saved = $service->uploadMultipleFiles($request->file('files'), $subDir);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'files'   => FileResource::collection($saved),
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Fallo al subir archivos', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }
}
