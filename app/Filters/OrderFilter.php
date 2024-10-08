<?php

namespace App\Filters;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderFilter
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

        if ($this->request->status) {
            $this->builder->whereIn('status_id', $this->request->status);
        }

        if(!isset($this->filters()['date_plan']['min']) && !isset($this->filters()['date_plan']['max']) && empty($this->filters())){
            $this->builder->where('date_plan', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('date_plan', '<=', Carbon::now()->format('Y-m-d') . ' 23:59:59');
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

    public function date_plan($value)
    {
        if ($value['min']) {
            $this->builder->where('date_plan', '>=', $value['min'] . ' 00:00:00');
        }

        if ($value['max']) {
            $this->builder->where('date_plan', '<=', $value['max'] . ' 23:59:59');
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

    public function contacts($value)
    {
        if (isset($value)) {
            $this->builder->whereHas('contact', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
