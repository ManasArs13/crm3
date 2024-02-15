<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\StatusMsService;
use Illuminate\Console\Command;

class ImportStatusMs extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-status';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import status from ms';

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
    public function handle(Option $option, MoySkladService $service, StatusMsService $statusMsService)
    {
        $url = Option::where('code', '=', 'ms_orders_status_url')->first()?->value;
        $service->createUrl($url,$statusMsService,[],'',1);
    }
}
