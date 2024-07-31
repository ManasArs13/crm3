<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\EmployeeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-employee {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import emloyees';

    /**
     * Execute the console command.
     */
    public function handle(MoySkladService $service, EmployeeService $employeeServices)
    {
        $url = Option::query()->where('code', '=', 'ms_employee_url')->first()?->value;

        $all = $this->option('all');

        $date = $all ? Carbon::now()->subYears(2) : Carbon::now()->subDays(3);
        $service->createUrl($url, $employeeServices);
    }
}
