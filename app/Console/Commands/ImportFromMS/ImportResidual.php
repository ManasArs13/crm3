<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Entity\ProductService;
use Illuminate\Console\Command;

class ImportResidual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-residual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ProductService $service): void
    {
        $service->importResidual();
    }
}
