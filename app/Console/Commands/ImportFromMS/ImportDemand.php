<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\DemandServices;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportDemand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-demand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(DemandServices $demandServices,  MoySkladService $service)
    {
        $url = Option::query()->where('code', '=', 'ms_url_demand')->first()?->value;
        $date = Option::query()->where('code', '=', 'ms_date_begin_change')->first()?->value;
        $service->createUrl($url,$demandServices,["updated"=>'>='.$date, "isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
    }
}
