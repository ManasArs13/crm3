<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use App\Services\Entity\DemandService;
use Carbon\Carbon;


class DeviationForShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processing:deviation {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет Цены норм и Сальдо для отгрузок';

    /**
     * Execute the console command.
     */
    public function handle(DemandService $demandService)
    {
        $all = $this->option('all');
        $date = $all?"2024-09-01 00:00:00": Carbon::now()->subDays(3);

        $demandService->DeviationForShipments($date);
    }
}
