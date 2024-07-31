<?php

namespace App\Console\Commands;

use App\Models\Option;
use App\Services\Entity\DemandService;
use Illuminate\Console\Command;
use App\Services\Api\MoySkladService;
use Illuminate\Support\Carbon;


class TempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'temp comm';

    /**
     * Execute the console command.
     */
    public function handle(DemandService $demandService, MoySkladService $service)
    {
        $url = Option::query()->where('code', '=', 'ms_url_demand')->first()?->value;
        $service->createUrl($url, $demandService, ["updated"=>'>='.'2024-01-01 00:00:00', "isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
    }
}
