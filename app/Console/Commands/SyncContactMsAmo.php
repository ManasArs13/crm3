<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\ContactAmo;
use App\Models\ContactAmoContact;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncContactMsAmo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-contact-ms-amo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $updatedAt = Option::query()->where('code','updated_at_sync_contact')->value('value');
        $contactMs = Contact::query()
            ->groupBy('phone_norm')
            ->havingRaw('COUNT(*) = 1')
            ->whereNotNull('contact_amo_link')
            ->where('contact_amo_link' ,'!=' ,'')
            ->where('updated_at','>',$updatedAt)->get();
        foreach ($contactMs as $contact){
            $contactAmoId =(integer) substr($contact->contact_amo_link,strrpos($contact->contact_amo_link,'/')+ 1);
            $contactAmoExist = ContactAmo::query()->where('id', $contactAmoId)->First();
            if ($contactAmoExist){
                $contactMsContactAmo = ContactAmoContact::query()->firstOrNew([
                    'contact_id'    =>  $contact->id,
                    'contact_amo_id'   =>  $contactAmoId,
                ]);
                    $contactMsContactAmo->contact_id = $contact->id;
                    $contactMsContactAmo->contact_amo_id = $contactAmoId;
                    $contactMsContactAmo->save();

                    $contact->contact_amo_link = 'https://euroblock.amocrm.ru/contacts/detail/' .$contactAmoId;
                    $contact->contact_amo_id = $contactAmoId;
                    $contact->update();

                    $contactAmoExist->contact_ms_link = 'https://online.moysklad.ru/#Company/edit?id=' .$contact->ms_id;
                    $contactAmoExist->contact_ms_id = $contact->ms_id;
                    $contactAmoExist->update();

            }
        }
        Option::query()->where('code','updated_at_sync_contact')->update([
            'value' => Carbon::now()->addMinutes(30)
        ]);
        Option::query()->updateOrCreate(
            ['code'     =>  MoySkladService::MS_DATE_BEGIN_CHANGE],
            ['value'    =>  Carbon::now()->addMinutes(30)]
        );
    }
}
