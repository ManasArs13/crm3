<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Services\Entity\ContactMsService;
use App\Services\Entity\OrderService;
use Illuminate\Console\Command;

class CkeckContactsMs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:ckeck-contacts-ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService, ContactMsService $contactMsService): void
    {
       // $orderService->checkContacts();
       $contactMsService->checkRows();
    }
}
