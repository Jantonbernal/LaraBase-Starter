<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\Loggable;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class UserObserver implements ShouldHandleEventsAfterCommit
{
    use Loggable;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->registerLog('success', 'Usuario creado', $user->getChanges());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->registerLog('success', 'Usuario actualizado', $user->getChanges());
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
