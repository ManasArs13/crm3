<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactAmo;
use App\Models\Errors;
use App\Models\ErrorTypes;


class ErrorController extends Controller
{

    public function update(){

        // Контакты АМО без менеджера

        $types = ErrorTypes::where('id', 1)->first();
        $now = now();

        ContactAmo::chunkById(500, function ($contacts) use ($types, $now) {
            $contactIds = $contacts->pluck('id')->toArray();

            $existingErrors = Errors::where('type_id', $types->id)
                ->whereIn('tab_id', $contactIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];
            $updateData = [];

            foreach ($contacts as $contact) {
                $link = url("/contactAmo/{$contact->id}/edit");

                if (is_null($contact->manager_id)) {
                    if (!isset($existingErrors[$contact->id])) {
                        $insertData[] = [
                            'status' => 1,
                            'allowed' => 1,
                            'type_id' => $types->id,
                            'link' => $link,
                            'description' => 'Отсутствует менеджер id',
                            'responsible_user' => $types->responsible,
                            'tab_id' => $contact->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                } else {
                    if (isset($existingErrors[$contact->id])) {
                        $updateData[] = [
                            'id' => $existingErrors[$contact->id]->id,
                            'status' => 0,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }

            if (!empty($updateData)) {
                foreach ($updateData as $data) {
                    Errors::where('id', $data['id'])->update([
                        'status' => $data['status'],
                        'updated_at' => $data['updated_at'],
                    ]);
                }
            }
        });

        return redirect()->back();
    }


}
