<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Api\MoySkladService;
use App\Services\Entity\OrderService;
use Illuminate\Console\Command;

class ReserveOrderMs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:reserve-order-ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $service, MoySkladService $moySkladService)
    {
        $service->reserve();
    }
}
