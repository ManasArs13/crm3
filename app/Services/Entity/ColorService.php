<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Color;

class ColorService implements EntityInterface
{
    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Color::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
                $entity->hex = 000000;
            }

            $entity->name = $row['name'];

            $entity->save();
        }
    }

}
