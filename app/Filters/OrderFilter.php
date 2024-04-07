<?php

namespace App\Filters;

use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderFilter
{
    protected $builder;
    protected $request;
    protected $column;
    protected $orderBy;
    protected $paginate;

    public function __construct(Builder $builder, $request, $column = 'id', $orderBy = 'asc', $paginate = 5)
    {
        $this->builder = $builder;
        $this->request = $request;
        $this->column = $column;
        $this->orderBy = $orderBy;
        $this->paginate = $paginate;
    }

    public function apply()
    {
        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder->orderBy($this->column, $this->orderBy)->paginate($this->paginate);
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

    public function date_plan($value)
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
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                });
        }

        if ($value == 'block') {

            $this->builder
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                });
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
