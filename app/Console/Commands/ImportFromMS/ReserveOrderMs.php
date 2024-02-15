<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\OrderMsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\OrderMs;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
    public function handle(OrderMsService $service, MoySkladService $moySkladService)
    {
        $service->reserve();
    }
}
