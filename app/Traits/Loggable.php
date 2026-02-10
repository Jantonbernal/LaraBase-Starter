<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

trait Loggable
{
    /**
     * Registra cualquier evento en la tabla logs.
     *
     * @param string $status   success | error
     * @param string $message  resumen
     * @param array  $payload  datos extra
     */
    public function registerLog(string $status, string $message, array $data = []): Log
    {
        return Log::create([
            'user_id' => Auth::id() ?? 1, // 1 para sistema/console
            'route'   => request()->path() ?? 'console', // Ruta solicitada por ejemplo: api/files/upload
            'method'  => request()->method() ?? 'CLI', // Método HTTP: GET, POST, PUT, DELETE
            'message' => $message,
            'payload' => json_encode([
                'attributes' => $data, // Aquí irá attributes o changes
                'ip'         => request()->ip(), // IP 
                'agent'      => request()->userAgent(),
                'http_code'  => http_response_code(),
            ]),
            'status'  => $status,
        ]);
    }

    /**
     * Centraliza el error, hace rollback, registra log y retorna respuesta JSON.
     */
    public function handleException(Throwable $e, string $customMessage = 'Error interno en el servidor'): JsonResponse
    {
        // 1. Rollback automático si hay una transacción
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        // 2. Registro del log usando el método anterior
        $log = $this->registerLog('error', $customMessage, [
            'exception' => $e->getMessage(),
            'trace'     => $e->getTraceAsString(),
        ]);

        // 3. Respuesta uniforme para el frontend
        return response()->json([
            'message' => $customMessage,
            'log_id'  => $log->id,
            'info'    => "Por favor, comunique este ID (#{$log->id}) al administrador."
        ], 500);
    }
}
