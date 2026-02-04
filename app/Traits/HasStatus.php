<?php

namespace App\Traits;

trait HasStatus
{
    public function toggleStatus(): self
    {
        $this->status = $this->status->toggle();
        $this->save();
        return $this;
    }

    public function getStatusLabel(): string
    {
        return $this->status->label();
    }
}
