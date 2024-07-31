<?php

namespace App\Console\Commands\SyncMS;

use App\Services\Entity\DemandService;
use App\Services\Entity\OrderService;
use Illuminate\Console\Command;


class CalcOfDeliveryPriceNorm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:calculation-of-delivery-price-norm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculation-of-delivery-price-norm';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService, DemandService $demandService)
    {
        $orderService->calcOfDeliveryPriceNorm();
        $demandService->calcOfDeliveryPriceNorm();
    }
}
