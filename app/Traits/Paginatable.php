<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Paginatable
{
    /**
     * Retorna la cantidad de elementos por página.
     */
    protected function getPerPage(Request $request, int $default = 10): int
    {
        // Prioridad: 1. El request (?per_page=) | 2. El default que pases en el 2do params | 3. 10 por defecto total
        return $request->query('per_page', $default);
    }
}
