<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use App\Services\Entity\DemandService;

class LateAndNoShipmentForTheOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processing:late-and-no-shipment-for-the-order {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет Опоздания в заказах';

    /**
     * Execute the console command.
     */
    public function handle(DemandService $demandService)
    {
        $all = $this->option('all');
        $date = $all?"2024-01-01 00:00:00": Carbon::now()->subDays(3);

        $demandService->calcLateAndNoShipmentForTheOrder($date);
    }
}
