<?php

namespace App\Entity\Amo;

use App\Filters\Amo\AmoOrderFilter;
use App\Models\OrderAmo;

class AmoOrderEntity
{
    public static function getForIndex($request, $selectColumn, $orderBy, $paginate = 50)
    {
        $builder = OrderAmo::query()->with(['status_amo', 'contact_amo']);

        $column = $selectColumn ? $selectColumn : 'id';
        $order = $orderBy ? $orderBy : 'desc';
        $count = $paginate ? $paginate : 50;

        $AmoOrders = (new AmoOrderFilter($builder, $request))->apply();

        if ($order == 'desc') {
            $AmoOrders->orderByDesc($column);
        } else {
            $AmoOrders->orderBy($column);
        }

        $AmoOrders = $AmoOrders->paginate($count);

        return $AmoOrders;
    }
}
