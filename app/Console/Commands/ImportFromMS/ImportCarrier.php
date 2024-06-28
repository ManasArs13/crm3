<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\CarrierService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCarrier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-carrier {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrier service';

    /**
     * Execute the console command.
     */
    public function handle(CarrierService $carrierService, MoySkladService $service)
    {
        $url = Option::where('code', '=', 'ms_carrier_url')->first()?->value;
        $service->createUrl($url, $carrierService);
    }
}
