<?php

namespace App\Filters\Inventory;

use Illuminate\Contracts\Database\Eloquent\Builder;

class EnterFilter
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

    public function moment($value)
    {
        if ($value['min']) {
            $this->builder->where('moment', '>=', $value['min'] . ' 00:00:00');
        }

        if ($value['max']) {
            $this->builder->where('moment', '<=', $value['max'] . ' 23:59:59');
        }
    }

    public function sum($value)
    {
        if ($value['min']) {
            $this->builder->where('sum', '>=', $value['min'] . ' 00:00:00');
        }

        if ($value['max']) {
            $this->builder->where('sum', '<=', $value['max'] . ' 23:59:59');
        }
    }
    
    public function contact($value)
    {
        if ($value !== 'index') {
            $this->builder->where('contact_id', $value);
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
