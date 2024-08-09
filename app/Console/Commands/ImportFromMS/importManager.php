<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ManagerService;
use Illuminate\Console\Command;

class ImportManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import Managers';

    /**
     * Execute the console command.
     */
    public function handle(MoySkladService $service, ManagerService $managerServices)
    {
        $url = Option::query()->where('code', '=', 'ms_manager_url')->first()?->value;
        $service->createUrl($url, $managerServices);
    }
}