<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Models\Option;
use App\Services\Api\AmoService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportFromAmo extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'amo:import-amo {--all}';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import amo';
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
    public function handle(AmoService $amoService): void
    {
        $all = $this->option('all');


        $amoService->getStatuses();
        // $amoService->getProducts();

        if ($all) {
            $amoService->getContactsAll();
            $amoService->getCallsAll();
            $amoService->getTalksAll();
        } else {
            $amoService->getContacts();
            $amoService->getCalls();
            $amoService->getTalks();
        }

        $amoService->getContacts();
        $amoService->getLeadsWithContacts();
        Option::query()
            ->where('code', AmoService::LAST_DATE_CODE)
            ->update(['value' => Carbon::now()->subDays(3)]);
    }
}
