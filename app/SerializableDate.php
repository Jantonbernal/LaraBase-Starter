<?php

namespace App;

use DateTimeInterface;

trait SerializableDate
{
    /**
     * Personaliza la serialización de fechas para JSON.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
