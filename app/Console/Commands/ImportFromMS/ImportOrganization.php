<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Entity\OrganizationService;
use Illuminate\Console\Command;
use App\Services\Api\MoySkladService;
use App\Models\Option;

class ImportOrganization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-organization';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт организации';

    /**
     * Execute the console command.
     */
    public function handle(OrganizationService $organizationService, MoySkladService $service)
    {
        $url_1 = Option::where('code', '=', 'ms_organization_url')->first()?->value;
        // $url_2 = Option::where('code', '=', 'ms_counterparty_report_url')->first()?->value;

        $service->createUrl($url_1, $organizationService);
    }
}
