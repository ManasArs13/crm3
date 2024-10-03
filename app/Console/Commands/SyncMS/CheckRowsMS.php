<?php

namespace App\Console\Commands\SyncMS;

use App\Services\Api\MoySkladService;
use App\Services\Entity\DemandService;
use App\Services\Entity\OrderService;
use App\Services\EntityMs\ShipmentMsService;
use Illuminate\Console\Command;


class CheckRowsMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:ckeck-rows-ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check orders and shipments from ms';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $OrderService, DemandService $demandService)
    {
        $OrderService->checkRows();
        $demandService->checkRows();
    }
}
