<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Manager;
use App\Models\Product;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Manager::query()
            ->select('id', 'name')
            ->withCount(['contacts as all_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY);
                    });
            }])
            ->withCount(['contacts as new_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY);
                    });
            }])
            ->with(['contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                        $query
                            ->select('id', 'suma', 'created_at', 'contact_id')
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY);
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }]);

        $entityItems = $builder->orderBy('id')->get();

        $contacts = Contact::query()
            ->select('id', 'manager_id', 'created_at')
            ->whereNull('manager_id')
            ->withSum(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'suma')
            ->whereHas('shipments', function ($que) use ($date, $dateY) {
                $que
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            })
            ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->select('id', 'suma', 'created_at', 'contact_id')
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->get();

        $selected = [
            "name",
            "count_contacts",
            'percent_contacts',
            "sum_shipments",
            "percent_shipments",
            "count_contacts_new",
            'percent_contacts_new',
            "sum_shipments_new",
            "percent_shipments_new",
        ];


        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateY',
            'dateRus',
            'contacts'
        ));
    }

    public function index_block(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Manager::query()
            ->select('id', 'name')
            ->withCount(['contacts as all_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::BLOCK);
                                });
                            });
                    });
            }])
            ->withCount(['contacts as new_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::BLOCK);
                                });
                            });
                    });
            }])
            ->with(['contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                        $query
                            ->select('id', 'suma', 'created_at', 'contact_id')
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::BLOCK);
                                });
                            });
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }]);

        $entityItems = $builder->orderBy('id')->get();

        $contacts = Contact::query()
            ->select('id', 'manager_id', 'created_at')
            ->whereNull('manager_id')
            ->withSum(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }], 'suma')
            ->whereHas('shipments', function ($que) use ($date, $dateY) {
                $que
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            })
            ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->select('id', 'suma', 'created_at', 'contact_id')
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }])

            ->get();

        $selected = [
            "name",
            "count_contacts",
            'percent_contacts',
            "sum_shipments",
            "percent_shipments",
            "count_contacts_new",
            'percent_contacts_new',
            "sum_shipments_new",
            "percent_shipments_new",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateY',
            'dateRus',
            'contacts'
        ));
    }

    public function index_concrete(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Manager::query()
            ->select('id', 'name')
            ->withCount(['contacts as all_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::CONCRETE);
                                });
                            });
                    });
            }])
            ->withCount(['contacts as new_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::CONCRETE);
                                });
                            });
                    });
            }])
            ->with(['contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                        $query
                            ->select('id', 'suma', 'created_at', 'contact_id')
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::CONCRETE);
                                });
                            });
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }]);

        $entityItems = $builder->orderBy('id')->get();

        $contacts = Contact::query()
            ->select('id', 'manager_id', 'created_at')
            ->whereNull('manager_id')
            ->withSum(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }], 'suma')
            ->whereHas('shipments', function ($que) use ($date, $dateY) {
                $que
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            })
            ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->select('id', 'suma', 'created_at', 'contact_id')
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }])
            ->get();

        $selected = [
            "name",
            "count_contacts",
            'percent_contacts',
            "sum_shipments",
            "percent_shipments",
            "count_contacts_new",
            'percent_contacts_new',
            "sum_shipments_new",
            "percent_shipments_new",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateY',
            'dateRus',
            'contacts'
        ));
    }
}
