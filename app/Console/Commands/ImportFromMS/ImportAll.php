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
        Artisan::call('ms:import-transport');

        Artisan::command('ms:import-categories {--date=null}', function () {
            $this->info("ms:import-categories {--date=not}!");
        });
        Artisan::command('ms:import-products {--date=null}', function () {
            $this->info("ms:import-products {--date=not}!");
        });

        Artisan::call('ms:import-delivery');

        Artisan::command('ms:import-contact {--date=all}', function () {
            $this->info("ms:import-contact {--date=not}!");
        });
        
        Artisan::call('ms:import-order');
        Artisan::call('ms:import-demand');

        Artisan::command('ms:import-supply {--date=null}', function () {
            $this->info("ms:import-supply {--date=not}!");
        });

        Artisan::call('ms:import-residual');

        Artisan::call('ms:import-tech-chart');
        Artisan::call('ms:import-processing');
        
    }
}
