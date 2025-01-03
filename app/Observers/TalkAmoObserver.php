<?php

namespace App\Observers;

use App\Models\TalkAmo;
use App\Services\Api\AmoService;
use Illuminate\Support\Facades\Artisan;

class TalkAmoObserver
{
    private $amoService;

    public function __construct(AmoService $amoService)
    {
        $this->amoService = $amoService;
    }

    public function created(TalkAmo $talkAmo): void
    {
        if ($talkAmo->amo_id) {
            $this->amoService->getTalk($talkAmo->amo_id);
        }
    }

    public function updated(TalkAmo $talkAmo): void
    {
        if ($talkAmo->amo_id) {
            $this->amoService->getTalk($talkAmo->amo_id);
        }
    }
}
