<?php

namespace App\Observers;

use App\Models\Contact;
use Illuminate\Support\Facades\Artisan;

class ContactMsObserver
{
    /**
     * Handle the ContactMs "created" event.
     */
    public function created(Contact $contact): void
    {
        Artisan::call('app:update-counterparty');
    }


}
