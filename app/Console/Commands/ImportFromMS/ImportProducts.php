<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ProductService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-products {--date=null}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import products from ms';

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
    public function handle(MoySkladService $service, ProductService $productService): void
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/product';

        //$date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $date = Carbon::now()->subDays(3);
        $service->createUrl($url, $productService, ["updated" => '>=' . $date], "productFolder");
    }
}
