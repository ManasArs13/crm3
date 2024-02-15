<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\CounterpartyService;
use Illuminate\Console\Command;

class UpdateCounterparty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-counterparty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(CounterpartyService $service ,MoySkladService $moySkladService )
    {
        $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $counterpartyUrl='https://api.moysklad.ru/api/remap/1.2/entity/counterparty?&filter='.$moySkladService->getFilter(["updated"=>'>='.$date]);
        $counterparty = $moySkladService->actionGetRowsFromJson($counterpartyUrl);
        $service->updateCounterpartyMs($counterparty);
    }
}
