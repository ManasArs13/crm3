<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Entity\OrganizationService;
use Illuminate\Console\Command;
use App\Services\Api\MoySkladService;
use App\Models\Option;

class ImportBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт баланса организации';

    /**
     * Execute the console command.
     */
    public function handle(OrganizationService $organizationService)
    {
        $organizationService->importBalance();
    }
}
