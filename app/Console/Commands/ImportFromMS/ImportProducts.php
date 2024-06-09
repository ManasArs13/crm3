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
    protected $signature = 'ms:import-products {--all}';

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
        $url = Option::where('code', '=', 'ms_product_url')->first()?->value;
        
        $all = $this->option('all');
        $date = $all ? Carbon::now()->subYears(2) : Carbon::now()->subDays(3);

        $service->createUrl($url, $productService, ["updated" => '>=' . $date], "productFolder");
    }
}
