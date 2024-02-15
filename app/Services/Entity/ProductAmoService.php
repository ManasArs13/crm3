<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Product;

class ProductAmoService implements EntityInterface
{
  public function import(array $rows)
  {
      foreach ($rows[0] as $data) {
          if ( isset($data->customFieldsValues[1]->values[0])){
              $productMsId = $data->customFieldsValues[1]->values[0]->value;
              if (!Product::query()->find($productMsId) ){
                  $price = (int)$data->customFieldsValues[0]->values[0]->value;
                  Product::query()->updateOrCreate(
                      ['id'=>$data->id],
                      [
                      'id'=>$data->id,
                      'name'=> $data->name,
                      'price'=> $price
                      ]);
                  }
              }
          }
      }
}
