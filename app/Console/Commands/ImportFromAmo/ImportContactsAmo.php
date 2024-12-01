<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Services\Api\AmoService;
use Illuminate\Console\Command;

class ImportContactsAmo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-contacts-amo {--all}';

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
        $all = $this->option('all');

        if ($all) {
            $amoService->getContactsAll();
        }else{
            $amoService->getContacts();
        }
    }
}
