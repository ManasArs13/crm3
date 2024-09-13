<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\PriceListService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportPriceList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-price-list {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Price Lists';

    /**
     * Execute the console command.
     */
    public function handle(Option $option, MoySkladService $service, PriceListService $priceListService)
    {
        $url = Option::where('code', '=', 'ms_price_lists_url')->first()?->value;

        $all = $this->option('all');
        $date = $all ? "2024-01-01 00:00:00" : Carbon::now()->subDays(3);
//https://api.moysklad.ru/api/remap/1.2/entity/pricelist/?limit=5&offset=0&filter=updated>=2024-01-01 00:00:00&expand=positions.assortment
        $service->createUrl($url, $priceListService, ["updated" => '>=' . $date], 'positions.assortment');
    }
}
