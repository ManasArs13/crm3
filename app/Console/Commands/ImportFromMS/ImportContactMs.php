<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ContactMsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportContactMs extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-contact';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import contact ms from ms';

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
    public function handle(Option $option, MoySkladService $service, ContactMsService $contactMsService)
    {

        $url = Option::where('code', '=', 'ms_counterparty_url')->first()?->value;
        $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $service->createUrl($url,$contactMsService,["updated"=>'>='.$date],'');

        $url = Option::where('code', '=', 'ms_counterparty_report_url')->first()?->value;
        $service->createUrl($url, $contactMsService,["updated"=>'>='.$date],'');
    }
}
