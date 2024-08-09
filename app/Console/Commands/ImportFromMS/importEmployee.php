<?php

namespace App\Console\Commands\ImportFromMS;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\EmployeeService;
use Illuminate\Console\Command;

class ImportEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-employee';

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
        $service->createUrl($url, $employeeServices);
    }
}
