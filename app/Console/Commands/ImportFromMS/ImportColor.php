<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Color;
use App\Models\Option;
use App\Services\Entity\ColorService;
use App\Services\Api\MoySkladService;
use Illuminate\Console\Command;

class ImportColor extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-color';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import color from ms';

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
    public function handle(Option $option, MoySkladService $service, ColorService $colorService)
    {
        $url = Option::where('code', '=', 'ms_color_url')->first()?->value;
        $service = $service;
        $service->createUrl($url,$colorService);
    }
}
