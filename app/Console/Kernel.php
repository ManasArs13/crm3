<?php

namespace App\Console;

use App\Console\Commands\CkeckContactsMs;
use App\Console\Commands\ImportFromAmo\ImportFromAmo;
use App\Console\Commands\ImportFromMS\ImportAll;
use App\Console\Commands\ImportFromMS\ImportBalance;
use App\Console\Commands\ImportFromMS\ImportCategories;
use App\Console\Commands\ImportFromMS\ImportColor;
use App\Console\Commands\ImportFromMS\ImportContactMs;
use App\Console\Commands\ImportFromMS\ImportDelivery;
use App\Console\Commands\ImportFromMS\ImportDemand;
use App\Console\Commands\ImportFromMS\ImportEmployee;
use App\Console\Commands\ImportFromMS\ImportInventory;
use App\Console\Commands\ImportFromMS\ImportPayment;
use App\Console\Commands\ImportFromMS\ImportOrderMs;
use App\Console\Commands\ImportFromMS\ImportOrganization;
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
        ImportFromAmo::class,
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
        ImportTransport::class,
        CkeckContactsMs::class,
        ImportEmployee::class,
        ImportPayment::class,
        ImportOrganization::class,
        ImportBalance::class,
        ImportInventory::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('amo:import-amo')->hourly();
        $schedule->command('app:sync-contact-ms-amo')->hourly();
        //$schedule->command('app:check-contact-amo')->hourly();
        //$schedule->command('app:update-counterparty')->hourly();
        $schedule->command('ms:ckeck-rows-ms')->dailyAt('01:00');
        $schedule->command('ms:ckeck-contacts-ms')->dailyAt('03:00');
        //$schedule->command('ms:import-employee')->everySixHours();

        $schedule->command('ms:import-carrier')->everySixHours();
        $schedule->command('ms:import-organization')->everySixHours();
        $schedule->command('ms:import-balance')->everyTenMinutes();
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
        $schedule->command('ms:import-supply')->everyTenMinutes();
        $schedule->command('ms:import-residual')->everyTenMinutes();
        $schedule->command('ms:import-inventory')->hourly();
        $schedule->command('ms:import-tech-chart')->hourly();
        $schedule->command('ms:import-processing')->hourly();
        $schedule->command('processing:late-and-no-shipment-for-the-order')->daily();
        $schedule->command('processing:deviation')->daily();
        $schedule->command('ms:import-price-list')->daily();
        $schedule->command('ms:import-payment')->hourly();

        $schedule->command('ms:import-contact --all')->everyThreeHours();

        $schedule->command('app:set-errors')->daily();
        $schedule->command('app:fill-corrects-and-positions')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
