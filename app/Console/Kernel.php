<?php

namespace App\Console;

use App\Console\Commands\ImportFromMS\ImportAll;
use App\Console\Commands\ImportFromMS\ImportCategories;
use App\Console\Commands\ImportFromMS\ImportColor;
use App\Console\Commands\ImportFromMS\ImportContactMs;
use App\Console\Commands\ImportFromMS\ImportDelivery;
use App\Console\Commands\ImportFromMS\ImportDemand;
use App\Console\Commands\ImportFromMS\ImportOrderMs;
use App\Console\Commands\ImportFromMS\ImportProcessing;
use App\Console\Commands\ImportFromMS\ImportProducts;
use App\Console\Commands\ImportFromMS\ImportResidual;
use App\Console\Commands\ImportFromMS\ImportStatusMs;
use App\Console\Commands\ImportFromMS\ImportSupply;
use App\Console\Commands\ImportFromMS\ImportTechChart;
use App\Console\Commands\ImportFromMS\ImportTransport;
use App\Console\Commands\ImportFromMS\ImportTransportType;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ImportAll::class,
        ImportCategories::class,
        ImportColor::class,
        ImportContactMs::class,
        ImportDelivery::class,
        ImportDemand::class,
        ImportOrderMs::class,
        ImportProcessing::class,
        ImportProducts::class,
        ImportResidual::class,
        ImportStatusMs::class,
        ImportSupply::class,
        ImportTechChart::class,
        ImportTransportType::class,
        ImportTransport::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('ms:import-all')->hourly();
        $schedule->command('ms:import-color')->everySixHours();
        $schedule->command('ms:import-status')->everySixHours();
        $schedule->command('ms:import-transport-type')->everySixHours();
        $schedule->command('ms:import-transport')->everySixHours();
        $schedule->command('ms:import-categories')->everySixHours();
        $schedule->command('ms:import-products')->everySixHours();
        $schedule->command('ms:import-delivery')->everySixHours();
        $schedule->command('ms:import-contact')->everyTenMinutes();
        $schedule->command('ms:import-order')->everyTenMinutes();
        $schedule->command('ms:import-demand')->everyTenMinutes();
        $schedule->command('ms:import-supply')->everySixHours();
        $schedule->command('ms:import-residual')->everySixHours();
        $schedule->command('ms:import-tech-chart')->everySixHours();
        $schedule->command('ms:import-processing')->everySixHours();

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}