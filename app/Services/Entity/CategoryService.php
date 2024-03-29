<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\Category;
use App\Services\Api\MoySkladService;

class CategoryService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Category::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
                $entity->is_active = 0;
            }

            $entity->name = $row['name'];
//            $group->description = \Arr::exists($row, 'description') ? $row['description'] : '';
            $entity->save();
        }
    }

    public function findActiveGroups(){
        $guids = Category::where('is_active', 1)
            ->select("id")
            ->get();

        return $guids;
    }
}
