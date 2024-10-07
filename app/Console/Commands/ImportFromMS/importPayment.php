<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Api\MoySkladService;
use App\Services\Entity\FinanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportPayment extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-payment {--all}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import payment from ms';

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
    public function handle(MoySkladService $service, FinanceService $FinanceService)
    {
        $urlCashout = 'https://api.moysklad.ru/api/remap/1.2/entity/cashout';
        $urlCashin = 'https://api.moysklad.ru/api/remap/1.2/entity/cashin';
        $urlPaymentout = 'https://api.moysklad.ru/api/remap/1.2/entity/paymentout';
        $urlPaymentin = 'https://api.moysklad.ru/api/remap/1.2/entity/paymentin';

        $all = $this->option('all');

        if ($all) {
            $service->createUrl($urlCashout, $FinanceService, []);
            $service->createUrl($urlCashin, $FinanceService, []);
            $service->createUrl($urlPaymentout, $FinanceService, []);
            $service->createUrl($urlPaymentin, $FinanceService, []);
        } else {
            $service->createUrl($urlCashout, $FinanceService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
            $service->createUrl($urlCashin, $FinanceService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
            $service->createUrl($urlPaymentout, $FinanceService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
            $service->createUrl($urlPaymentin, $FinanceService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
        }  

    }
}
