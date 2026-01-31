<?php

namespace App\Observers;

use App\Loggable;
use App\Models\Company;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class CompanyObserver implements ShouldHandleEventsAfterCommit
{
    use Loggable;

    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        $this->registerLog('success', 'Empresa actualizada', $company->getChanges());
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
