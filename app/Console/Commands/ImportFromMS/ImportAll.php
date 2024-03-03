<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Color;
use App\Models\Option;
use App\Services\Entity\ColorService;
use App\Services\Api\MoySkladService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportAll extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'ms:import-all';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import all from ms';

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
        Artisan::call('ms:import-color');
    }
}
