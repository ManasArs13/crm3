<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Services\Api\AmoService;
use Illuminate\Console\Command;

class UpdateContactsAmo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-contacts-amo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(AmoService $amoService)
    {
         $amoService->updateContacts();
    }
}
