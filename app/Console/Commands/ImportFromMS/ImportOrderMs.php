<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\OrderService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportOrderMs extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-order';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import orders from ms';

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
    public function handle(Option $option, MoySkladService $service, OrderService $orderService)
    {
        $url = Option::where('code', '=', 'ms_orders_url')->first()?->value;
    //    $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $date = Carbon::now()->subDays(5);
        $service->createUrl($url, $orderService, ["updated"=>'>='.$date, "isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
    }
}
