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
    protected $signature = 'ms:import-contact {--all}';

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

        $all = $this->option('all');

        if ($all) {
            $service->createUrl($url_1, $contactMsService, [] , '');
            $service->createUrl($url_2, $contactMsService, [] , '');
        } else {
            $service->createUrl($url_1, $contactMsService, ["updated" => '>=' . Carbon::now()->subMonths(3)], '');
            $service->createUrl($url_2, $contactMsService, ["updated" => '>=' . Carbon::now()->subMonths(3)], '');
        }
    }
}
