<?php

namespace App\Console\Commands\ImportFromAmo;

use Illuminate\Console\Command;
use App\Services\Api\AmoService;


class ImportAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-access-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(AmoService $amoService): void
    {
        $amoService->getAccessToken();
    }
}
