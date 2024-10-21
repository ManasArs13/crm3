<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Services\Api\AmoService;
use Illuminate\Console\Command;

class ImportTalks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amo:import-talks {--all}';

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
    { // TODO import talks
        // $all = $this->option('all');

        // if ($all) {
        //     $amoService->getTalks();
        // } else {
        //     $amoService->getTalks();
        // }
    }
}
