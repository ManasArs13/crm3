<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Manager;
use App\Models\Option;
use App\Models\OrderAmo;
use App\Models\Product;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Double;

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

        $percent_of_the_block_sale = (float) Option::where('code', '=', 'percent_of_the_block_sale')->first()?->value;
        $percent_of_the_concrete_sale = (float) Option::where('code', '=', 'percent_of_the_concrete_sale')->first()?->value;

        $percent = $percent_of_the_block_sale + $percent_of_the_concrete_sale;

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

        // Salary
        $managers_for_salary_block = Manager::query()
            ->select('id', 'name')
            ->whereNot('id', 4)
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
            }])->get();
        $managers_for_salary_concrete = Manager::query()
            ->select('id', 'name')
            ->whereNot('id', 4)
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
            }])->get();

        $total_sum_block_yaroslav = 0;
        $total_sum_concrete_yaroslav = 0;

        $total_sum_block_ekaterina = 0;
        $total_sum_concrete_ekaterina = 0;

        $total_sum_block_euroblock = 0;
        $total_sum_concrete_euroblock = 0;

        foreach ($managers_for_salary_block as $entityItem) {
            switch ($entityItem->name) {
                case 'Ярослав':
                    $total_sum_block_yaroslav = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'блок') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
                case 'Екатерина':
                    $total_sum_block_ekaterina = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'блок') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
                case 'Общая Еврогрупп':
                    $total_sum_block_euroblock = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'блок') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
            }
        }

        foreach ($managers_for_salary_concrete as $entityItem) {
            switch ($entityItem->name) {
                case 'Ярослав':
                    $total_sum_concrete_yaroslav = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'бетон') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
                case 'Екатерина':
                    $total_sum_concrete_ekaterina = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'бетон') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
                case 'Общая Еврогрупп':
                    $total_sum_concrete_euroblock = $entityItem->contacts->sum(function ($contact) {
                        $sum = 0;

                        foreach ($contact->shipments as $shipment) {
                            if ($shipment->products) {
                                foreach ($shipment->products as $product) {
                                    if ($product->product->building_material == 'бетон') {
                                        $sum += $product->price * $product->quantity;
                                    }
                                }
                            }
                        }

                        return $sum;
                    });
                    break;
            }
        }

        $salary_yaroslav_block = $percent_of_the_block_sale * $total_sum_block_euroblock + 2 * $percent_of_the_block_sale * $total_sum_block_yaroslav;
        $salary_ekaterina_block = $percent_of_the_block_sale * $total_sum_block_euroblock + 2 * $percent_of_the_block_sale * $total_sum_block_ekaterina;

        $salary_yaroslav_concrete = $percent_of_the_concrete_sale * $total_sum_concrete_euroblock + 2 * $percent_of_the_concrete_sale * $total_sum_concrete_yaroslav;
        $salary_ekaterina_concrete = $percent_of_the_concrete_sale * $total_sum_concrete_euroblock + 2 * $percent_of_the_concrete_sale * $total_sum_concrete_ekaterina;

        $total_salary_yaroslav = number_format($salary_yaroslav_block + $salary_yaroslav_concrete, 0, '', ' ',);
        $total_salary_ekaterina = number_format($salary_ekaterina_block + $salary_ekaterina_concrete, 0, '', ' ',);

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

        $managers_without_dilevery = Manager::query()
            ->select('id', 'name')
            ->whereNot('id', 4)
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
                            })->with('products');
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }])->orderBy('id')->get();

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
                    ->where('is_success', true)
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
            ->where('is_success', true)
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
            'total_salary_yaroslav',
            'total_salary_ekaterina',
            'percent',
            'entityName',
            'entityItems',
            'managers_without_dilevery',
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

        $percent = (float) Option::where('code', '=', 'percent_of_the_block_sale')->first()?->value;

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

        $managers_without_dilevery = Manager::query()
            ->select('id', 'name')
            ->whereNot('id', 4)
            ->with(['contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                        $query
                            ->select('id', 'suma', 'created_at', 'contact_id')
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries
                                        ->where('building_material', Product::BLOCK);
                                });
                            })->with('products');
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }])->orderBy('id')->get();

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
            'percent',
            'entityName',
            'entityItems',
            'managers_without_dilevery',
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

        $percent = (float) Option::where('code', '=', 'percent_of_the_concrete_sale')->first()?->value;

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

        $managers_without_dilevery = Manager::query()
            ->select('id', 'name')
            ->whereNot('id', 4)
            ->with(['contacts' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->with(['shipments' => function (Builder $query) use ($date, $dateY) {
                        $query
                            ->select('id', 'suma', 'created_at', 'contact_id')
                            ->whereMonth('created_at', $date)
                            ->whereYear('created_at', $dateY)
                            ->whereHas('products', function ($query) {
                                $query->whereHas('product', function ($queries) {
                                    $queries
                                        ->where('building_material', Product::CONCRETE);
                                });
                            })->with('products');
                    }])
                    ->select('id', 'manager_id', 'created_at');
            }])->orderBy('id')->get();

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
            'percent',
            'entityName',
            'entityItems',
            'managers_without_dilevery',
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

    public function managerTwo(Request $request){
        $entityName = 'Сводка менеджеров 2';

        $month_list = array(
            '01' => 'январь',
            '02' => 'февраль',
            '03' => 'март',
            '04' => 'апрель',
            '05' => 'май',
            '06' => 'июнь',
            '07' => 'июль',
            '08' => 'август',
            '09' => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        $year = Carbon::now()->year;
        $daysInMonth = Carbon::createFromDate($year, $date)->daysInMonth;
        $startOfMonth = Carbon::createFromDate($year, $date, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromDate($year, $date, 1)->endOfMonth()->toDateString();

        $tables = [
            'incoming_calls',
            'outgoing_calls',
            'talk_amos',
            'orders_created',
            'orders_closed',
            'orders_success',
            'conversion',
            'shipments'
        ];
        $emptyValues = array_fill_keys($tables, 0);

        $report = [];
        $totals = [];
        $managers = ['Yaroslav' => 'Ярослав', 'Ekatirina' => 'Екатерина', 'Other' => 'Остальные', 'All' => 'Всего'];


        foreach ($tables as $table) {
            if($table === 'incoming_calls'){
                $records = DB::table('calls')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN employee_amo_id = 7 THEN "Yaroslav" WHEN employee_amo_id = 11 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startOfMonth, $endOfMonth])
                    ->where('type', 'incoming_call')
                    ->where('duration', '>', 0)
                    ->groupBy(DB::raw('DATE(created_at)'), 'employee_group')
                    ->get();


            } elseif($table === 'outgoing_calls'){
                $records = DB::table('calls')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN employee_amo_id = 7 THEN "Yaroslav" WHEN employee_amo_id = 11 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startOfMonth, $endOfMonth])
                    ->where('type', 'outgoing_call')
                    ->where('duration', '>', 0)
                    ->groupBy(DB::raw('DATE(created_at)'), 'employee_group')
                    ->get();
            } elseif($table === 'talk_amos'){
                $records = DB::table('talk_amos')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN employee_amo_id = 7 THEN "Yaroslav" WHEN employee_amo_id = 11 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(created_at)'), 'employee_group')
                    ->get();
            } elseif($table === 'orders_created'){
                $records = DB::table('order_amos')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN manager_id = 1 THEN "Yaroslav" WHEN manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->where('is_success', 1)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(created_at)'), 'employee_group')
                    ->get();
            } elseif($table === 'orders_closed'){
                $records = DB::table('order_amos')
                    ->select(
                        DB::raw('DATE(closed_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN manager_id = 1 THEN "Yaroslav" WHEN manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->where('is_success', 1)
                    ->where('status_amo_id', 143)
                    ->whereBetween(DB::raw('DATE(closed_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(closed_at)'), 'employee_group')
                    ->get();
            } elseif($table === 'orders_success'){
                $records = DB::table('order_amos')
                    ->select(
                        DB::raw('DATE(closed_at) as date'),
                        DB::raw('COUNT(*) as count'),
                        DB::raw('CASE WHEN manager_id = 1 THEN "Yaroslav" WHEN manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->where('is_success', 1)
                    ->where('status_amo_id', 142)
                    ->whereBetween(DB::raw('DATE(closed_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(closed_at)'), 'employee_group')
                    ->get();
            } elseif ($table === 'shipments') {
                $records = DB::table('orders')
                    ->select(
                        DB::raw('DATE(shipments.created_at) as date'),
                        DB::raw('COUNT(shipments.id) as count'),
                        DB::raw('CASE WHEN orders.manager_id = 1 THEN "Yaroslav" WHEN orders.manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
                    )
                    ->Join('shipments', 'orders.id', '=', 'shipments.order_id')
                    ->whereBetween(DB::raw('DATE(shipments.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(shipments.created_at)'), 'employee_group')
                    ->get();

            }

            foreach ($records as $record) {
                $report[$record->date][$record->employee_group][$table] = $record->count;
            }
        }


        for ($i = 0; $i < $daysInMonth; $i++) {
            $day = Carbon::createFromDate($year, $date, 1)->startOfMonth()->addDays($i)->toDateString();

            if (!isset($report[$day])) {
                $report[$day] = [
                    'Yaroslav' => $emptyValues,
                    'Ekatirina' => $emptyValues,
                    'Other' => $emptyValues,
                ];
            }

            $report[$day]['All'] = array_fill_keys($tables, 0);
            foreach($report[$day] as $name){
                foreach($tables as $table){
                    $report[$day]['All'][$table] += $name[$table] ?? 0;
                }
            }

        }


        $employeeGroups = ['Yaroslav' => 7, 'Ekatirina' => 11, 'Other' => null];
        $allDates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $allDates[] = Carbon::createFromDate($year, $date, $i)->toDateString();
        }

        $successOrdersData = DB::table('order_amos')
            ->select(
                DB::raw('DATE(closed_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('CASE WHEN manager_id = 1 THEN "Yaroslav" WHEN manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
            )
            ->where('is_success', 1)
            ->where('status_amo_id', 142)
            ->whereBetween(DB::raw('DATE(closed_at)'), [
                Carbon::createFromDate($year, $date, 1)->subMonth()->startOfMonth()->toDateString(),
                Carbon::createFromDate($year, $date, 1)->endOfMonth()->toDateString()
            ])
            ->groupBy(DB::raw('DATE(closed_at)'), 'employee_group')
            ->get()
            ->groupBy(['date', 'employee_group']);


        $closedOrdersData = DB::table('order_amos')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('CASE WHEN manager_id = 1 THEN "Yaroslav" WHEN manager_id = 2 THEN "Ekatirina" ELSE "Other" END as employee_group')
            )
            ->where('is_success', 1)
            ->whereBetween(DB::raw('DATE(created_at)'), [
                Carbon::createFromDate($year, $date, 1)->subMonth()->startOfMonth()->toDateString(),
                Carbon::createFromDate($year, $date, 1)->endOfMonth()->toDateString()
            ])
            ->groupBy(DB::raw('DATE(created_at)'), 'employee_group')
            ->get()
            ->groupBy(['date', 'employee_group']);


        foreach ($allDates as $currentDate) {
            $startDate = Carbon::parse($currentDate)->subMonth()->toDateString();
            $totalSuccessOrders = 0;
            $totalClosedOrders = 0;

            foreach ($employeeGroups as $groupName => $groupId) {
                $successOrders = 0;
                $closedOrders = 0;

                foreach ($successOrdersData as $date => $groups) {
                    if ($date >= $startDate && $date <= $currentDate && isset($groups[$groupName])) {
                        $successOrders += $groups[$groupName]->sum('count');
                    }
                }

                foreach ($closedOrdersData as $date => $groups) {
                    if ($date >= $startDate && $date <= $currentDate && isset($groups[$groupName])) {
                        $closedOrders += $groups[$groupName]->sum('count');
                    }
                }

                $conversion = $closedOrders > 0 ? round(($successOrders / $closedOrders) * 100, 1) . '%' : 0;
                $report[$currentDate][$groupName]['conversion'] = $conversion;

                $totalSuccessOrders += $successOrders;
                $totalClosedOrders += $closedOrders;
            }

            $report[$currentDate]['All']['conversion'] = $totalClosedOrders > 0 ? round( ($totalSuccessOrders / $totalClosedOrders) * 100, 1) . '%' : 0;
        }



        $selected = [
            "incoming_calls",
            "outgoing_calls",
            "conversations",
            "created_transactions",
            "closed_transactions",
            "success_transactions",
            "conversion",
            "shipments"
        ];
        foreach ($selected as $column) {

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }


        return view("manager.byDays", compact(
            'entityName',
            "resColumns",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'tables',
            'report',
            'totals',
            'managers'
        ));
    }
}
