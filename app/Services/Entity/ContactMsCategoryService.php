<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\ContactCategory;
use Illuminate\Support\Arr;

class ContactMsCategoryService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['tags'] as $row) {

            $entity = ContactCategory::firstOrNew(['name' => $row]);
            $entity->name=$row;
            $entity->save();
        }
    }

}
