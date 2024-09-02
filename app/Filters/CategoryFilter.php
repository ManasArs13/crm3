<?php

namespace App\Filters;

use App\Models\Category;
//use Illuminate\Contracts\Database\Eloquent\Builder;

class CategoryFilter
{
    protected $builder;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    /* Тип */
    public function typeCHeck()
    {
        if ($this->request->type == 'products') {
            $entity = 'products_categories';
            $entityItems = Category::query()->where('type', Category::PRODUCTS);
        } else if ($this->request->type == 'materials') {
            $entity = 'products_categories_materials';
            $entityItems = Category::query()->where('type', Category::MATERIAL);
        } else {
            $entity = 'products_categories';
            $entityItems = Category::query();
        }

        return ['entity' => $entity, 'entityItems' => $entityItems];

    }

    /* Фильтры для отображения */
    public function filters($entityItems){
        foreach ($this->request->filters as $key => $value) {
            if ($key == 'created_at' || $key == 'updated_at') {
                $entityItems
                    ->where($key, '>=', $value['min'] . ' 00:00:00')
                    ->where($key, '<=', $value['max'] . ' 23:59:59');
            } else if ($key == 'weight_kg' || $key == 'price') {
                $entityItems
                    ->where($key, '>=', $value['min'])
                    ->where($key, '<=', $value['max']);
            }
        }

        return $entityItems;
    }

    /* Сортировка */
    public function sort($entityItems){
        if (isset($this->request->orderBy)  && $this->request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($this->request->column)->orderByDesc('sort')->paginate(50);
            $orderBy = 'desc';
        } else if (isset($this->request->orderBy)  && $this->request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($this->request->column)->orderByDesc('sort')->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =   $entityItems->paginate(50);
        }
        return ['orderBy' => $orderBy, 'entityItems' => $entityItems];
    }










}
