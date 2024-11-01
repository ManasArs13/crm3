<?php

namespace App\Console\Commands\ImportFromMS;

use App\Services\Entity\OrganizationService;
use Illuminate\Console\Command;
use App\Services\Api\MoySkladService;
use App\Models\Option;
use App\Services\Entity\EnterService;
use App\Services\Entity\LossService;
use Carbon\Carbon;

class ImportInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:import-inventory {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт оприходования и списания';

    /**
     * Execute the console command.
     */
    public function handle(EnterService $enterService, LossService $lossService, MoySkladService $service)
    {
        $url_enter = Option::where('code', '=', 'ms_enter_url')->first()?->value;
        $url_loss = Option::where('code', '=', 'ms_loss_url')->first()?->value;

        $all = $this->option('all');

        if ($all) {
            $service->createUrl($url_enter, $enterService);
            $service->createUrl($url_loss, $lossService);
        } else {
            $service->createUrl($url_enter, $enterService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
            $service->createUrl($url_loss, $lossService, ["updated" => '>=' . Carbon::now()->subDays(3)]);
        }
    }
}
