<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\TransportService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportTransport extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-transport';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import transport from ms';

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
    public function handle(Option $option, MoySkladService $service, TransportService $transportService)
    {
        $url = Option::where('code', '=', 'ms_transport_url')->first()?->value;
        $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $service->createUrl($url,$transportService,["updated"=>'>='.$date],'');
    }
}
