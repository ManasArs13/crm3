<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\CategoryService;
use Illuminate\Console\Command;

class ImportCategories extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-categories {--date=null}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import categories for products from ms';

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
    public function handle(Option $option, MoySkladService $service, CategoryService $CategoryService)
    {
        $url = Option::where('code', '=', 'ms_productfolder_url')->first()?->value;

        if ($this->option('date') == 'null') {

            $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
            $service->createUrl($url, $CategoryService, ["updated"=>'>='.$date], '');

        } else if($this->option('date') == 'not') {

            $service->createUrl($url, $CategoryService);

        } else {

            $date = $this->option('date');
            $service->createUrl($url, $CategoryService, ["updated"=>'>='.$date], '');

        }
      
    }
}
