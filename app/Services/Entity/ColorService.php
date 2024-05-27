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
            $entity->hex = (\Arr::exists($row, 'code') && trim($row["code"]) != '') ? $row['code'] : 000000;
            $entity->font_color = (\Arr::exists($row, 'description') && trim($row["description"]) != '') ? $row['description'] : 000000;

            $entity->save();
        }
    }

}
