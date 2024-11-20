<?php

namespace App\Filters;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PaymentFilter
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

        if (isset($this->request->type_filter)) {
            if ($this->request->type_filter === 'income') {
                $this->builder->whereRaw("type IN ('paymentin', 'cashin')");
            } elseif ($this->request->type_filter === 'expense') {
                $this->builder->whereRaw("type NOT IN ('paymentin', 'cashin')");
            }
        }

        if(!isset($this->filters()['moment']['min']) && !isset($this->filters()['moment']['max']) && empty($this->filters())){
            $this->builder->where('moment', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('moment', '<=', Carbon::now()->format('Y-m-d') . ' 23:59:59');
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

    public function type($value)
    {
        switch ($value) {
            case 'cashin':
                $this->builder->where('type', 'cashin');
                break;
            case 'cashout':
                $this->builder->where('type', 'cashout');
                break;
            case 'paymentin':
                $this->builder->where('type', 'paymentin');
                break;
            case 'paymentout':
                $this->builder->where('type', 'paymentout');
                break;
        }
    }

    public function sum($value)
    {
        if ($value['min']) {
            $this->builder->where('sum', '>=', $value['min']);
        }

        if ($value['max']) {
            $this->builder->where('sum', '<=', $value['max']);
        }
    }

    public function sortable_sum($value){
        if ($value === 'income') {
            $this->builder->whereRaw("type IN ('paymentin', 'cashin')");
        } elseif ($value === 'expense') {
            $this->builder->whereRaw("type NOT IN ('paymentin', 'cashin')");
        }
    }

    public function filters()
    {
        return $this->request->filters ? $this->request->filters : [];
    }
}
