<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Product;

class SupplyPositionFilter
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
                    $query->where('building_material', Product::CONCRETE);
                });
        }

        if ($value == 'block') {
            $this->builder
                ->whereHas('products', function ($query) {
                    $query->where('building_material', Product::BLOCK);
                });
        }
    }

    public function contacts($value)
    {
        if (isset($value)) {
            $this->builder
                ->whereHas('supply', function ($query) use ($value) {
                    $query->whereHas('contact', function ($query) use ($value) {
                        $query->whereIn('id', $value);
                    });
                });
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
