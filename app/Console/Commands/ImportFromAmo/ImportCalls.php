<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Models\Option;
use App\Services\Api\AmoService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCalls extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'amo:import-calls {--all}';

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

        if ($all) {
            $amoService->getCallsAll();
        } else {
            $amoService->getCalls();
        }

    }
}
