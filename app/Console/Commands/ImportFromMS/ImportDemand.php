<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\DemandService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportDemand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-demand {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(DemandService $demandService, MoySkladService $service)
    {
        $all = $this->option('all');

        $url = Option::query()->where('code', '=', 'ms_url_demand')->first()?->value;

        if($all) {
            $service->createUrl($url, $demandService, ["isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
        } else {
            $service->createUrl($url, $demandService, ["updated"=>'>='.Carbon::now()->subDays(3), "isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
        }
    }
}
