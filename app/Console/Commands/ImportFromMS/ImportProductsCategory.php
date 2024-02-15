<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Models\ProductsCategory;
use App\Services\Api\MoySkladService;
use App\Services\Entity\DeliveryService;
use App\Services\Entity\ProductsCategoryService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportProductsCategory extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-productsCategory';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import productsCategory from ms';

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
    public function handle(Option $option, MoySkladService $service, ProductsCategoryService $productsCategoryService)
    {
        $url = Option::where('code', '=', 'ms_productfolder_url')->first()?->value;
        $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $service->createUrl($url,$productsCategoryService,["updated"=>'>='.$date],'');
    }
}
