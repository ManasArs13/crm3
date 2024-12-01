<?php

namespace App\Console\Commands\ImportFromAmo;

use App\Models\Option;
use App\Services\Api\AmoService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportLeadsFromAmo extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     * @var string
     */
    protected $signature = 'app:import-leads-amo';

    /**
     * Описание консольной команды.
     * @var string
     */
    protected $description = 'Import leads amo';
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
        $amoService->getLeadsWithContacts();
    }
}
