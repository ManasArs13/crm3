<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\TechChartService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportTechChart extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-tech-chart {--all}';

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
        $all = $this->option('all');

        if ($all) {
            $service->createUrl($url, $techChart);
        } else {
            $service->createUrl($url, $techChart, ["updated" => '>=' . Carbon::now()->subDays(3)]); 
        } 

    }
}
