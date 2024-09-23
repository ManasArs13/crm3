<?php

namespace App\Filters;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ShipmentFilter
{
    protected $builder;
    protected $request;

    public function __construct(Builder $builder, $request)
    {
        $this->builder = $builder;
        $this->request = $request;
    }

    public function apply()
    {
        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        if(!isset($this->filters()['created_at']['min']) && !isset($this->filters()['created_at']['max']) && empty($this->filters())){
            $this->builder->where('created_at', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
                ->where('created_at', '<=', Carbon::now()->format('Y-m-d') . ' 23:59:59');
        }

        return $this->builder;
    }

    public function created_at($value)
    {
        if ($value['min']) {
            $this->builder->where('created_at', '>=', $value['min'] . ' 00:00:00');
        }

        if ($value['max']) {
            $this->builder->where('created_at', '<=', $value['max'] . ' 23:59:59');
        }
    }

    public function updated_at($value)
    {
        if ($value['min']) {
            $this->builder->where('updated_at', '>=', $value['min'] . ' 00:00:00');
        }

        if ($value['max']) {
            $this->builder->where('updated_at', '<=', $value['max'] . ' 23:59:59');
        }
    }

    public function material($value)
    {
        if ($value == 'concrete') {

            $this->builder
                ->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                });
        }

        if ($value == 'block') {

            $this->builder
                ->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                });
        }
    }

    public function delivery($value)
    {
        if ($value !== 'index') {
            $this->builder->where('delivery_id', $value);
        }
    }

    public function status($value)
    {
        if ($value !== 'index') {
            $this->builder->where('status', $value);
        }
    }

    public function transport($value)
    {
        if ($value !== 'index') {
            $this->builder->where('transport_id', $value);
        }
    }

    public function shipments_debt($value)
    {
        if ($value) {
            return $this->builder->whereIn('transport_id', function ($query) {
                $query->select(DB::raw('distinct(t1.transport_id)'))
                    ->from("shipments as t1")
                    ->join(DB::raw('(select min(id) as id, transport_id from shipments where status <>"Не оплачен" and transport_id is not null group by transport_id) as t0'), 't1.transport_id', '=', 't0.transport_id')
                    ->whereRaw('t1.id > t0.id');
            })
                ->join("contacts", "contacts.id", "=", "shipments.contact_id")
                ->where('shipments.status', '=', 'Не оплачен')
                ->where('contacts.balance', '<', 0);
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
