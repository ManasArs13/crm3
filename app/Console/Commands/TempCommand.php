<?php

namespace App\Console\Commands;

use App\Models\Option;
use App\Services\Entity\DemandServices;
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
    public function handle(DemandServices $demandServices, MoySkladService $service)
    {
<<<<<<< HEAD

        $url = Option::query()->where('code', '=', 'ms_url_demand')->first()?->value;

=======
        $all = $this->option('all');
        $url = Option::query()->where('code', '=', 'ms_url_demand')->first()?->value;

        $date = $all ? Carbon::now()->subYears(2) : Carbon::now()->subDays(3);
>>>>>>> 49b448e7767ede1c227b0cfd38dedf6a19de7e83
        $service->createUrl($url,$demandServices, ["updated"=>'>='.'2024-01-01 00:00:00', "isDeleted"=>["true","false"]],'positions.assortment,attributes.value,agent,state');
    }
}
