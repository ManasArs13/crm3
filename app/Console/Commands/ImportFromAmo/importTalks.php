<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Services\Api\AmoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    { 
        $all = $this->option('all');

        if ($all) {
            $amoService->getTalksAll();
        } else {
            $amoService->getTalks();
        }
    }
}
