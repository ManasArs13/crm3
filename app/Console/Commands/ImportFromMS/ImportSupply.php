<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\SupplyService;
use Illuminate\Console\Command;

class ImportSupply extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-supply {--date=null}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import supply from ms';

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
    public function handle(MoySkladService $service, SupplyService $supplyService): void
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/supply';

        if ($this->option('date') == 'null') {

            $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
            $service->createUrl($url, $supplyService, ["updated"=>'>='.$date],'');
        } else if ($this->option('date') == 'not') {

            $service->createUrl($url, $supplyService, [],'');
        } else {

            $date = $this->option('date');
            $service->createUrl($url, $supplyService, ["updated"=>'>='.$date],'');
        }
    }
}
