<?php

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasCode
{
    /**
     * Boot del trait para asignar el código automáticamente.
     */
    protected static function bootHasCode()
    {
        static::creating(function ($model) {
            // Verificamos si la tabla tiene la columna 'code' para evitar errores de SQL
            if (Schema::hasColumn($model->getTable(), 'code')) {
                // Solo genera el código si no se ha asignado manualmente
                if (!$model->code) {
                    $model->code = $model->generateUniqueCode();
                }
            }
        });
    }

    /**
     * Genera un código basado en el prefijo definido en el modelo.
     */
    public function generateUniqueCode(): string
    {
        $prefix = defined(static::class . '::CODE_PREFIX') ? constant(static::class . '::CODE_PREFIX') : 'GEN';

        // Obtenemos el último ID y generamos el siguiente número
        $lastRecord = static::latest('id')->first();
        $nextNumber = $lastRecord ? ($lastRecord->id + 1) : 1;

        // Formato: USU-00001
        $code = $prefix . '-' . Str::padLeft($nextNumber, 5, '0');

        // Verificación de unicidad por si acaso
        while (static::where('code', $code)->exists()) {
            $nextNumber++;
            $code = $prefix . '-' . Str::padLeft($nextNumber, 5, '0');
        }

        return $code;
    }
}
