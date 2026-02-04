<?php

namespace App\Observers;

use App\Models\Menu;
use App\Traits\Loggable;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class MenuObserver implements ShouldHandleEventsAfterCommit
{
    use Loggable;

    /**
     * Handle the Menu "created" event.
     */
    public function created(Menu $menu): void
    {
        $this->registerLog('success', 'Menú creado', $menu->toArray());
    }

    /**
     * Handle the Menu "updated" event.
     */
    public function updated(Menu $menu): void
    {
        $this->registerLog('success', 'Menú actualizado', $menu->getChanges());
    }

    /**
     * Handle the Menu "deleted" event.
     */
    public function deleted(Menu $menu): void
    {
        //
    }

    /**
     * Handle the Menu "restored" event.
     */
    public function restored(Menu $menu): void
    {
        //
    }

    /**
     * Handle the Menu "force deleted" event.
     */
    public function forceDeleted(Menu $menu): void
    {
        //
    }
}
