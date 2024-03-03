<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Color;

class ColorService implements EntityInterface
{
    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Color::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
                $entity->hex = 000000;
            }

            $entity->name = $row['name'];

            $entity->save();
        }
    }

}
