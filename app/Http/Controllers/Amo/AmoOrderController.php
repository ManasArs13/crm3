<?php

namespace App\Http\Controllers\Amo;

use App\Entity\Amo\AmoOrderEntity;
use App\Helpers\Columns;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\OrderAmo;

class AmoOrderController extends Controller
{
    public function index(AmoOrderRequest $request)
    {
        $entityName = 'Заказы АМО';

        $orderBy = $request->orderBy == 'asc' ? 'desc' : 'asc';
        $selectColumn = $request->column;

        $entityItems = AmoOrderEntity::getForIndex($request, $request->column, $request->orderBy, 50);

        $columns = Columns::get(new OrderAmo, $request->columns);

        $filtersBy = [];

        return view('amo.order.index', compact(
            'entityName',
            'entityItems',
            'columns',
            'orderBy',
            'selectColumn',
            'filtersBy'
        ));
    }
}
