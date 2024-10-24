<?php

namespace App\Filters;

//use Illuminate\Contracts\Database\Eloquent\Builder;

class ContactAmoFilter
{
    protected $builder;
    protected $request;

    public function __construct($builder, $request)
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

    public function managers($value)
    {
        if (isset($value) && $value != 'all') {
            $this->builder->where('manager_id', $value);
        }
    }

    public function is_success($value)
    {
        if (isset($value) && $value != 'all') {
            $this->builder->where('is_success', $value);
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
