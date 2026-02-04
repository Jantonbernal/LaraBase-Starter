<?php

namespace App\Observers;

use App\Models\Role;
use App\Traits\Loggable;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class RoleObserver implements ShouldHandleEventsAfterCommit
{
    use Loggable;

    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        $this->registerLog('success', 'Rol creado', $role->toArray());
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->registerLog('success', 'Rol actualizada', $role->getChanges());
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}
