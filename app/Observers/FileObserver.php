<?php

namespace App\Observers;

use App\Models\File;
use App\Traits\Loggable;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class FileObserver implements ShouldHandleEventsAfterCommit
{
    use Loggable;

    /**
     * Handle the File "created" event.
     */
    public function created(File $file): void
    {
        $this->registerLog('success', 'Archivo creado', $file->getAttributes());
    }

    /**
     * Handle the File "updated" event.
     */
    public function updated(File $file): void
    {
        //
    }

    /**
     * Handle the File "deleted" event.
     */
    public function deleted(File $file): void
    {
        //
    }

    /**
     * Handle the File "restored" event.
     */
    public function restored(File $file): void
    {
        //
    }

    /**
     * Handle the File "force deleted" event.
     */
    public function forceDeleted(File $file): void
    {
        //
    }
}
