<?php

namespace App\Console\Commands\ImportFromMS;

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
    public function handle()
    {
        Artisan::call('ms:import-color');
        Artisan::call('ms:import-status');
        Artisan::call('ms:import-transport-type');
        Artisan::call('ms:import-transport --all');

        Artisan::call('ms:import-categories --all');
        Artisan::call('ms:import-products --all');
        Artisan::call('ms:import-delivery --all');
        Artisan::call('ms:import-contact --all');
       
        Artisan::call('ms:import-order --all');
        Artisan::call('ms:import-demand --all');

        Artisan::call('ms:import-supply --all');

        Artisan::call('ms:import-residual');

        Artisan::call('ms:import-tech-chart');
        Artisan::call('ms:import-processing --all');
        
    }
}
