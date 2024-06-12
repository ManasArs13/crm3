<?php

namespace App\Console\Commands\ImportFromMS;

use Illuminate\Console\Command;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\Entity\ContactMsCategoryService;

class ImportContactMsCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-contact-ms-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories of contacts from ms';

    /**
     * Execute the console command.
     */
    public function handle(MoySkladService $service, ContactMsCategoryService $contactmsCategoryService)
    {
        $url = Option::where('code', '=', 'ms_counterparty_meta_url')->first()?->value;
        $service->createUrl($url, $contactmsCategoryService,[],"",1);
    }
}
