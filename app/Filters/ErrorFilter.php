<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

class ErrorFilter
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

        // if ($this->request->status) {
        //     $this->builder->whereIn('status_id', $this->request->status);
        // }

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

    public function contacts($value)
    {
        if (isset($value)) {
            $this->builder->whereHas('contact_amo', function ($query) use ($value) {
                $query->whereIn('id', $value);
            });
        }
    }

    public function type($value)
    {
        if (isset($value) && $value !== 'index') {
            $this->builder->whereHas('type', function ($query) use ($value) {
                $query->where('id', $value);
            });
        }
    }

    public function allowed($value)
    {
        if (isset($value) && $value !== 'index') {
            $this->builder->where('allowed', $value);
        }
    }

    public function status($value)
    {
        if (isset($value) && $value !== 'index') {
            $this->builder->where('status', $value);
        }
    }

    public function responsible($value)
    {
        if (isset($value) && $value !== 'index') {
            $this->builder->where('responsible_user', $value);
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}