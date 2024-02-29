<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\VehicleType;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\TransportTypeService;
use App\Services\Entity\VehicleTypeService;
use Illuminate\Console\Command;

class ImportTransportType extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-transport-type';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import transport type from ms';

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
    public function handle(Option $option, MoySkladService $service, TransportTypeService $transportTypeService)
    {
        $url = Option::where('code', '=', 'ms_vehicle_type_url')->first()?->value;
        $service->createUrl($url, $transportTypeService);
    }
}
