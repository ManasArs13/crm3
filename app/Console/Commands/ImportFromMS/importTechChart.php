<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\TechChartService;
use Illuminate\Console\Command;

class ImportTechChart extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-tech-chart';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import technical charts from ms';

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
    public function handle(MoySkladService $service, TechChartService $techChart)
    {
        $url = Option::where('code', '=', 'ms_tech_chart_url')->first()?->value;
        $service->createUrl($url, $techChart);
    }
}
