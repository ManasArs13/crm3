<?php

namespace App\Observers\Production;

use App\Models\TechProcess;

class ProcessingObserer
{
    public function created(TechProcess $techProcess): void
    {
        //
    }

    public function updated(TechProcess $techProcess): void
    {
        //
    }

    /**
     * Handle the TechProcess "deleted" event.
     */
    public function deleted(TechProcess $techProcess): void
    {
        //
    }

    /**
     * Handle the TechProcess "restored" event.
     */
    public function restored(TechProcess $techProcess): void
    {
        //
    }

    /**
     * Handle the TechProcess "force deleted" event.
     */
    public function forceDeleted(TechProcess $techProcess): void
    {
        //
    }
}
