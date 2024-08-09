<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ManagerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-manager {--all}';

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

        $all = $this->option('all');

        $date = $all ? Carbon::now()->subYears(2) : Carbon::now()->subDays(3);
        $service->createUrl($url, $managerServices);
    }
}
