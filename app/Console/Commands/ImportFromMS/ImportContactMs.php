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
    protected $signature = 'ms:import-contact {--date=all}';

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

        $url_1 = Option::where('code', '=', 'ms_counterparty_url')->first()?->value;
        $url_2 = Option::where('code', '=', 'ms_counterparty_report_url')->first()?->value;

        if ($this->option('date') == 'null') {

            $date = Carbon::now()->subWeek();
            $service->createUrl($url_1, $contactMsService, ["updated"=>'>='.$date],'');
            $service->createUrl($url_2, $contactMsService, ["updated"=>'>='.$date],'');
        } else if ($this->option('date') == 'all') {

            $service->createUrl($url_1, $contactMsService, [], '');
            $service->createUrl($url_2, $contactMsService, [], '');
        } else {

            $date = $this->option('date');
            $service->createUrl($url_1, $contactMsService, ["updated"=>'>='.$date], '');
            $service->createUrl($url_2, $contactMsService, ["updated"=>'>='.$date], '');
        }
    }
}
