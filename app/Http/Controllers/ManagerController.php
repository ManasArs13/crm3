<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Manager;
use App\Models\OrderAmo;
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
            ->whereNot('id', 4)
            ->withCount(['contacts as all_contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('shipments', function ($que) use ($date, $dateY) {
                        $que
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries->where('building_material', Product::BLOCK)
                                        ->orWhere('building_material', Product::CONCRETE);
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
                                    $queries->where('building_material', Product::BLOCK)
                                        ->orWhere('building_material', Product::CONCRETE);
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
                                    $queries->where('building_material', Product::BLOCK)
                                        ->orWhere('building_material', Product::CONCRETE);
                                });
                            });
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }]);

        $entityItems = $builder->orderBy('id')->get();

        // Contacts without Manager
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
                    ->whereYear('created_at', $dateY)
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK)
                                ->orWhere('building_material', Product::CONCRETE);
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
                            $queries->where('building_material', Product::BLOCK)
                                ->orWhere('building_material', Product::CONCRETE);
                        });
                    });
            }])
            ->get();

        // Columns
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

        // Columns AMO
        $selectedAmo = [
            'name',
            "all_orders",
            "percent_all_orders",
            "success_orders",
            "percent_success_orders",
            "no_success_orders",
            "percent_no_success_orders",
        ];

        foreach ($selectedAmo as $column) {
            $resColumnsAllAmo[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedAmo)) {
                $resColumnsAmo[$column] = trans("column." . $column);
            }
        }

        // Managers AMO
        $AmoManagers = Manager::query()
            ->whereNot('id', 4)
            ->withCount(['order_amos as all_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    //->whereNotIn('status_amo_id', [142, 143])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 142)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as no_success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 143)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])->orderBy('id')->get();

        // Orders Amo without managers
        $amo_orders['name'] = "Не выбрано";

        $amo_orders["all_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            //->whereNotIn('status_amo_id', [142, 143])
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 142)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["no_success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 143)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        // Contacts
        $contactsWithOrders = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->whereHas('orders', function ($query) use ($date) {
                $query->select('id', 'contact_id', 'status_id', 'created_at')
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })->get();

        $contactsWithShipments = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->WhereHas('shipments', function ($query) use ($date) {
                $query->select('id', 'contact_id', 'created_at')
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->get();



        foreach ($contactsWithOrders as $contact) {
            if (!$contactsWithShipments->firstWhere('id', $contact->id)) {
                $contactsWithShipments->push($contact);
            }
        }

        $contactsWithCount = $contactsWithShipments->sortBy('name');

        // Columns contacts
        $selectedContacts = [
            "name",
            "manager_name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selectedContacts as $column) {
            $resColumnsAllContacts[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedContacts)) {
                $resColumnsContacts[$column] = trans("column." . $column);
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
            'contacts',
            'AmoManagers',
            "resColumnsAmo",
            "resColumnsAllAmo",
            'amo_orders',
            'contactsWithCount',
            'selectedContacts',
            "resColumnsContacts",
            "resColumnsAllContacts",
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
            ->whereNot('id', 4)
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

        // Contacts without Manager
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

        // Columns
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

        // Columns AMO
        $selectedAmo = [
            'name',
            "all_orders",
            "percent_all_orders",
            "success_orders",
            "percent_success_orders",
            "no_success_orders",
            "percent_no_success_orders",
        ];

        foreach ($selectedAmo as $column) {
            $resColumnsAllAmo[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedAmo)) {
                $resColumnsAmo[$column] = trans("column." . $column);
            }
        }

        // Managers AMO
        $AmoManagers = Manager::query()
            ->whereNot('id', 4)
            ->withCount(['order_amos as all_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    //->whereNotIn('status_amo_id', [142, 143])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 142)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as no_success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 143)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])->orderBy('id')->get();

        // Orders Amo without managers
        $amo_orders['name'] = "Не выбрано";

        $amo_orders["all_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            //->whereNotIn('status_amo_id', [142, 143])
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 142)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["no_success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 143)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        // Contacts
        $contactsWithOrders = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->whereHas('orders', function ($query) use ($date) {
                $query
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    })
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })->get();

        $contactsWithShipments = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->WhereHas('shipments', function ($query) use ($date) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })->get();



        foreach ($contactsWithOrders as $contact) {
            if (!$contactsWithShipments->firstWhere('id', $contact->id)) {
                $contactsWithShipments->push($contact);
            }
        }

        $contactsWithCount = $contactsWithShipments->sortBy('name');

        // Columns contacts
        $selectedContacts = [
            "name",
            "manager_name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selectedContacts as $column) {
            $resColumnsAllContacts[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedContacts)) {
                $resColumnsContacts[$column] = trans("column." . $column);
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
            'contacts',
            'AmoManagers',
            "resColumnsAmo",
            "resColumnsAllAmo",
            'amo_orders',
            'contactsWithCount',
            'selectedContacts',
            "resColumnsContacts",
            "resColumnsAllContacts",
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
            ->whereNot('id', 4)
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

        // Contacts without Manager
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


        // Columns
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

        // Columns AMO
        $selectedAmo = [
            'name',
            "all_orders",
            "percent_all_orders",
            "success_orders",
            "percent_success_orders",
            "no_success_orders",
            "percent_no_success_orders",
        ];

        foreach ($selectedAmo as $column) {
            $resColumnsAllAmo[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedAmo)) {
                $resColumnsAmo[$column] = trans("column." . $column);
            }
        }

        // Managers AMO
        $AmoManagers = Manager::query()
            ->whereNot('id', 4)
            ->withCount(['order_amos as all_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    //->whereNotIn('status_amo_id', [142, 143])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 142)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withCount(['order_amos as no_success_orders' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->where('status_amo_id', 143)
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])->orderBy('id')->get();

        // Orders Amo without managers
        $amo_orders['name'] = "Не выбрано";

        $amo_orders["all_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            //->whereNotIn('status_amo_id', [142, 143])
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 142)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        $amo_orders["no_success_orders"] = OrderAmo::query()
            ->whereNull('manager_id')
            ->where('status_amo_id', 143)
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY)
            ->count();

        // Contacts
        $contactsWithOrders = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->whereHas('orders', function ($query) use ($date) {
                $query
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    })
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })->get();

        $contactsWithShipments = Contact::query()->select('id', 'name', 'ms_id', 'manager_id')
            ->with([
                'manager:id,name',
                'orders' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'sum')
                        ->whereIn('status_id', [5, 6])
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                },
                'shipments' => function (Builder $query) use ($date) {
                    $query
                        ->select('id', 'name', 'contact_id', 'suma')
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                }
            ])
            ->WhereHas('shipments', function ($query) use ($date) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })->get();



        foreach ($contactsWithOrders as $contact) {
            if (!$contactsWithShipments->firstWhere('id', $contact->id)) {
                $contactsWithShipments->push($contact);
            }
        }

        $contactsWithCount = $contactsWithShipments->sortBy('name');

        // Columns contacts
        $selectedContacts = [
            "name",
            "manager_name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selectedContacts as $column) {
            $resColumnsAllContacts[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selectedContacts)) {
                $resColumnsContacts[$column] = trans("column." . $column);
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
            'contacts',
            'AmoManagers',
            "resColumnsAmo",
            "resColumnsAllAmo",
            'amo_orders',
            'contactsWithCount',
            'selectedContacts',
            "resColumnsContacts",
            "resColumnsAllContacts",
        ));
    }
}
