<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ProcessingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportProcessing extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-processing {--all}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import processing from ms';

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
    public function handle(MoySkladService $service, ProcessingService $processingService)
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/processing';

        // $date = Option::where('code', '=', 'ms_date_begin_change')->first()?->value;
        $all = $this->option('all');
        $date = $all ? Carbon::now()->subYears(2) : Carbon::now()->subDays(3);

        $service->createUrl($url, $processingService, ["updated" => '>=' . $date], '');
    }
}
