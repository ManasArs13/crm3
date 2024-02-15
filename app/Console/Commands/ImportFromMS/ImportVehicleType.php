<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\VehicleType;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\VehicleTypeService;
use Illuminate\Console\Command;

class ImportVehicleType extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-vehicleType';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import vehicleType from ms';

    /**
     * Создать новый экземпляр команды.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Выполнить консольную команду.
     */
    public function handle(Option $option, MoySkladService $service, VehicleTypeService $vehicleTypeService)
    {
        $url = Option::where('code', '=', 'ms_vehicle_type_url')->first()?->value;
        $service = $service;
        $service->createUrl($url,$vehicleTypeService);
    }
}
