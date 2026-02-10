<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

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
}
