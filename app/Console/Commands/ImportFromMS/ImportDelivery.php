<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\DeliveryService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportDelivery extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-delivery';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import delivery from ms';

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
    public function handle(Option $option, MoySkladService $service, DeliveryService $deliveryService): void
    {
        $url = Option::query()->where('code', '=', 'ms_delivery_url')->first()?->value;
        $date = Option::query()->where('code', '=', 'ms_date_begin_change')->first()?->value;
        $service->createUrl($url,$deliveryService,["updated"=>'>='.$date],'');
    }
}
