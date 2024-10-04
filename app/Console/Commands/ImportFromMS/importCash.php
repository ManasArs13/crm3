<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Cashin;
use App\Models\Cashout;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\CashService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCash extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-cash {--all}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import cash from ms';

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
    public function handle(Option $option, MoySkladService $service, CashService $CashService)
    {
        $urlCashout = 'https://api.moysklad.ru/api/remap/1.2/entity/cashout';
        $urlCashin = 'https://api.moysklad.ru/api/remap/1.2/entity/cashin';

        $all = $this->option('all');

        if ($all) {
            $service->createUrl($urlCashout, $CashService, []);
            $service->createUrl($urlCashin, $CashService, []);
        } else {
            $service->createUrl($urlCashout, $CashService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
            $service->createUrl($urlCashin, $CashService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
        }  

    }
}
