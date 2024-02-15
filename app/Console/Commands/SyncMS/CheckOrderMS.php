<?php

namespace App\Console\Commands\SyncMS;

use App\Services\Api\MoySkladService;
use App\Services\Entity\OrderMsService;
use Illuminate\Console\Command;


class CheckOrderMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:ckeck-order-ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check orders from ms';

    /**
     * Execute the console command.
     */
    public function handle(OrderMsService $service, MoySkladService $moySkladService)
    {
        $service->checkRows();
    }
}
