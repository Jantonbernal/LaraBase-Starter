<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;

trait HandlesStatus
{
    protected function respondWithStatus(Model $model, string $singularName): JsonResponse
    {
        $model->toggleStatus();

        return response()->json([
            'message' => "{$singularName} {$model->getStatusLabel()}",
            'data'    => $model,
        ]);
    }
}
