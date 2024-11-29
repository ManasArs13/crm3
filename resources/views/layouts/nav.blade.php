<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="w-11/12 mx-auto max-w-10xl">
        <div class="flex !flex-row justify-between h-16">
            <div class="flex !flex-row">

                <!-- Logo -->
                <div class="shrink-0 flex !flex-row lg:hidden items-center">
                    <a href="{{ route('dashboard') }}" class="uppercase fond-semibold text-indigo-400">
                        crm-euroblock
                    </a>
                </div>

                <!-- main Links -->
                <div class="hidden space-x-3 lg:flex">
                    @can(['home'])
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') ||
                            request()->routeIs('dashboard-2') ||
                            request()->routeIs('dashboard-3')">
                            Главная
                        </x-nav-link>
                    @endcan
                    @can('order')
                        <x-nav-link :href="route('order.index')" :active="request()->routeIs('order.*')">
                            Заказы
                        </x-nav-link>
                    @endcan
                    @can('payment')
                        <x-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">
                            Платежи
                        </x-nav-link>
                    @endcan
                    @can('shipment')
                        <x-nav-link :href="route('shipment.index')" :active="request()->routeIs('shipment.*') && !request()->routeIs('shipment.index2')">
                            {{ __('title.shipments') }}
                        </x-nav-link>
                    @endcan

                    @can('calculator')
                        {{-- Калькулятор --}}
                        <x-nav-link :href="route('calculator.block') . '#content-1'">
                            Калькулятор
                        </x-nav-link>
                    @endcan

                    @canany(['residual', 'supply', 'supply_position', 'loss', 'enter', 'journal_material'])
                        {{-- Производство --}}
                        <div class="hidden md:flex md:items-center md:ms-1">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Склад</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('residual')
                                        <x-dropdown-link :href="route('residual.blocksCategories')" :active="request()->routeIs('residual.*')">
                                            Остатки
                                        </x-dropdown-link>
                                    @endcan
                                    @can('supply')
                                        <x-dropdown-link :href="route('supply.index')">
                                            Приёмки
                                        </x-dropdown-link>
                                    @endcan
                                    @can('supply_position')
                                        <x-dropdown-link :href="route('supply_positions.index')">
                                            Позиции приёмок
                                        </x-dropdown-link>
                                    @endcan
                                    @can('loss')
                                        <x-dropdown-link :href="route('loss.index')">
                                            Списания
                                        </x-dropdown-link>
                                    @endcan
                                    @can('enter')
                                        <x-dropdown-link :href="route('enter.index')">
                                            Оприходования
                                        </x-dropdown-link>
                                    @endcan
                                    @can('journal_material')
                                        <x-dropdown-link :href="route('report.material_manager')">
                                            Журнал материалы
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany

                    @canany(['techchart', 'techprocess'])
                        {{-- Производство --}}
                        <div class="hidden md:flex md:items-center md:ms-1">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Производство</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('techchart')
                                        <x-dropdown-link :href="route('techcharts.index')" :active="request()->routeIs('techcharts.*')">
                                            Техкарты
                                        </x-dropdown-link>
                                    @endcan
                                    @can('techprocess')
                                        <x-dropdown-link :href="route('processings.index')" :active="request()->routeIs('processings.*')">
                                            Техоперции
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany

                    @canany([
                        'transporter_fee', 'contact',
                        'product', 'material', 'transport',
                        'shift', 'transport_type', 'delivery',
                        'delivery_price', 'category_product',
                        'error', 'error_type'
                    ])
                        {{-- Справочник --}}
                        <div class="hidden md:flex md:items-center md:ms-1">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Справочник</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">

                                    @can('transporter_fee')
                                        <x-dropdown-link :href="route('report.transporter_fee')">
                                            Перевозчики
                                        </x-dropdown-link>
                                    @endcan

                                    @can('contact')
                                        <x-dropdown-link :href="route('contact.index')">
                                            Контакты
                                        </x-dropdown-link>
                                    @endcan
                                    @can('product')
                                        <x-dropdown-link :href="route('product.index', ['type' => 'products'])">
                                            Товары
                                        </x-dropdown-link>
                                    @endcan
                                    @can('material')
                                        <x-dropdown-link :href="route('product.index', ['type' => 'materials'])">
                                            Материалы
                                        </x-dropdown-link>
                                    @endcan
                                    @can('transport')
                                        <x-dropdown-link :href="route('transport.index')">
                                            Весь транспорт
                                        </x-dropdown-link>
                                    @endcan
                                    @can('shift')
                                        <x-dropdown-link :href="route('transport.shift.index')">
                                            Смены
                                        </x-dropdown-link>
                                    @endcan
                                    @can('transport_type')
                                        <x-dropdown-link :href="route('transportType.index')">
                                            Виды ТС
                                        </x-dropdown-link>
                                    @endcan
                                    @can('delivery')
                                        <x-dropdown-link :href="route('delivery.index')">
                                            Доставка
                                        </x-dropdown-link>
                                    @endcan
                                    @can('delivery_price')
                                        <x-dropdown-link :href="route('shiping_price.index')">
                                            Прайс (доставка)
                                        </x-dropdown-link>
                                    @endcan
                                    @can('category_product')
                                        <x-dropdown-link :href="route('category.index')">
                                            Категории товаров
                                        </x-dropdown-link>
                                    @endcan
                                    @can('error')
                                        <x-dropdown-link :href="route('errors.index')">
                                            Реестр ошибок
                                        </x-dropdown-link>
                                    @endcan
                                    @can('error_type')
                                        <x-dropdown-link :href="route('errorTypes.index')">
                                            справочник ошибок
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany

                    @canany([
                        'supply', 'order_position', 'shipment_position',
                        'supply_position', 'operator_order', 'operator_shipment'
                    ])
                    {{-- Прочее --}}
                        <div class="hidden md:flex md:items-center md:ms-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Прочее</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('order_position')
                                        <x-dropdown-link :href="route('order_positions.index')">
                                            Позиции заказов
                                        </x-dropdown-link>
                                    @endcan
                                    @can('shipment_position')
                                        <x-dropdown-link :href="route('shipment_products.index')">
                                            Позиции отгрузок
                                        </x-dropdown-link>
                                    @endcan
                                    @can('operator_order')
                                        <x-dropdown-link :href="route('operator.orders')">
                                            Оператор заказы
                                        </x-dropdown-link>
                                    @endcan
                                    @can('operator_shipment')
                                        <x-dropdown-link :href="route('operator.shipments')">
                                            Оператор отгрузки
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany
                        @role('operator')
                            @can('operator_order')
                                <x-nav-link :href="route('operator.orders')" :active="request()->routeIs('operator.orders')">
                                    Заказы
                                </x-nav-link>
                            @endcan
                            @can('operator_shipment')
                                <x-nav-link :href="route('operator.shipments')" :active="request()->routeIs('operator.shipments')">
                                    {{ __('title.shipments') }}
                                </x-nav-link>
                            @endcan
                        @endrole

                    @canany([
                        'amo_order', 'amo_contact', 'contact_link',
                        'double_order', 'call', 'conversation'
                    ])
                        {{-- Приход - расход --}}
                        <div class="hidden md:flex md:items-center md:ms-1">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>AMO</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('amo_order')
                                        <x-dropdown-link :href="route('amo-order.index')">
                                            Заказы
                                        </x-dropdown-link>
                                    @endcan
                                    @can('amo_contact')
                                        <x-dropdown-link :href="route('contactAmo.index')">
                                            Контакты АМО
                                        </x-dropdown-link>
                                    @endcan
                                    @can('contact_link')
                                        <x-dropdown-link :href="route('bunch_of_contacts')">
                                            Связка контактов
                                        </x-dropdown-link>
                                    @endcan
                                    @can('double_order')
                                        <x-dropdown-link :href="route('double_of_orders')">
                                            Дубли сделок
                                        </x-dropdown-link>
                                    @endcan
                                    @can('call')
                                        <x-dropdown-link :href="route('calls')">
                                            Звонки
                                        </x-dropdown-link>
                                    @endcan
                                    @can('conversation')
                                        <x-dropdown-link :href="route('conversations')">
                                            Беседы
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany
                    @canany(['user', 'user_permission'])
                        <div class="hidden md:flex md:items-center md:ms-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Пользователи</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('user')
                                        <x-dropdown-link :href="route('users.all')">
                                            Пользователи
                                        </x-dropdown-link>
                                    @endcan
                                    @can('user_permission')
                                        <x-dropdown-link :href="route('permission')">
                                            Разрешения
                                        </x-dropdown-link>
                                    @endcan

                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany

                    @canany([
                        'report_manager', 'report_manager_two', 'report_day',
                        'report_deviation', 'report_delivery_category', 'report_delivery',
                        'report_transport', 'report_transporter', 'report_counterparty',
                        'report_summary', 'report_summary_remains',
                    ])
                        <div class="hidden md:flex md:items-center md:ms-6">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Сводка</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">

                                    @can('report_manager')
                                        <x-dropdown-link :href="route('manager.index')">
                                            Сводка - Менеджеры
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_manager_two')
                                        <x-dropdown-link :href="route('manager.managerTwo')">
                                            Сводка - Менеджеры 2
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_manager')
                                        <x-dropdown-link :href="route('manager.managerShipments')">
                                            Отгр. по менеджерам
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_day')
                                        <x-dropdown-link :href="route('report.days')">
                                            Сводка по дням
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_deviation')
                                        <x-dropdown-link :href="route('report.deviations')">
                                            Сводка - Отклонения
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_delivery_category')
                                        <x-dropdown-link :href="route('report.delivery.category')">
                                            Сводка по доставке
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_delivery')
                                        <x-dropdown-link :href="route('report.delivery')">
                                            Сводка - Все доставки
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_transport')
                                        <x-dropdown-link :href="route('report.transport')">
                                            Сводка - Транспорт
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_transporter')
                                        <x-dropdown-link :href="route('report.transporter')">
                                            Сводка - Перевозчик
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_counterparty')
                                        <x-dropdown-link :href="route('report.counteparty')">
                                            Сводка - Контрагенты
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_summary')
                                        <x-dropdown-link :href="route('summary.index')">
                                            {{ __('title.summary') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @can('report_summary_remains')
                                        <x-dropdown-link :href="route('summary.remains')">
                                            {{ __('title.summaryRemains') }}
                                        </x-dropdown-link>
                                    @endcan

                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcanany

                    {{-- Должники --}}
                    @can('debtor')
                        @if (request()->get('debtors_balance') < -3000000)
                            <div
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-800 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                <a href="{{ route('debtors') }}" class="bg-red-300 p-1 rounded-lg">Должники</a>
                            </div>
                        @else
                            <x-nav-link :href="route('debtors')" :active="request()->routeIs('debtors')">
                                Должники
                            </x-nav-link>
                        @endif
                    @endcan

                </div>
            </div>

            @if (Auth::user())
                <!-- Settings Dropdown -->
                <div class="hidden lg:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user() ? Auth::user()->name : '' }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @can('option')
                                <x-dropdown-link :href="route('option.index')">
                                    Опции
                                </x-dropdown-link>
                            @endcan

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    Выйти
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            <!-- Hamburger -->
            <div class="-me-2 flex !flex-row items-center lg:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden">
        <div class="fixed inset-0 z-10"></div>
        <div
            class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10 z-50">

            <div class="flex !flex-row items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <div class="text-lg font-bold">CRM</div>
                </a>
                <div class="-me-2 flex items-center lg:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>




            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">

                        @can('home')
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Главная

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- 'Product' sub-menu, show/hide based on menu state. -->
                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                    <a href="{{ route('dashboard') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Все</a>
                                    <a href="{{ route('dashboard-2') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Блок</a>
                                    <a href="{{ route('dashboard-3') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Бетон</a>
                                </div>
                            </div>
                        @endcan
                        @can('order')
                            <a href="{{ route('order.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                        @endcan
                        @can('shipment')
                            <a href="{{ route('shipment.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.shipments') }}</a>
                        @endcan

                        @role('operator')
                            @can('operator_order')
                                <a href="{{ route('operator.orders') }}"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                            @endcan
                            @can('operator_shipment')
                                <a href="{{ route('operator.shipments') }}"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Отгрузки</a>
                            @endcan
                        @endrole

                        @can('calculator')
                            <a href="{{ route('calculator.block') . '#content-1' }}"
                               class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Калькулятор</a>
                        @endcan

                        @canany(['residual', 'supply', 'supply_position', 'loss', 'enter', 'report_manager_two'])
                            <div class="-mx-3">
                                <button type="button"
                                        class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Склад

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- 'Product' sub-menu, show/hide based on menu state. -->
                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                    @can('residual')
                                        <a href="{{ route('residual.blocksCategories') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Остатки</a>
                                    @endcan
                                    @can('supply')
                                        <a href="{{ route('supply.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Приемки</a>
                                    @endcan
                                    @can('supply_position')
                                        <a href="{{ route('supply_positions.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции приёмок</a>
                                    @endcan
                                    @can('loss')
                                        <a href="{{ route('loss.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Списания</a>
                                    @endcan
                                    @can('enter')
                                        <a href="{{ route('enter.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Оприходования</a>
                                    @endcan
                                    @can('journal_material')
                                        <a href="{{ route('report.material_manager') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Журнал материалы</a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany

                        @canany(['techchart', 'techprocess'])
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Производство

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- 'Product' sub-menu, show/hide based on menu state. -->
                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                    @can('techchart')
                                        <a href="{{ route('techcharts.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Техкарты</a>
                                    @endcan
                                    @can('techprocess')
                                        <a href="{{ route('processings.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Техоперации</a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany

                        @canany([
                            'transporter_fee', 'contact', 'product',
                            'material', 'transport', 'shift',
                            'transport_type', 'delivery', 'delivery_price',
                            'category_product', 'error', 'error_type'
                        ])
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Справочник

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>


                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                    @can('transporter_fee')
                                        <a href="{{ route('report.transporter_fee') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Перевозчики</a>
                                    @endcan
                                    @can('contact')
                                        <a href="{{ route('contact.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Контакты</a>
                                    @endcan
                                    @can('product')
                                        <a href="{{ route('product.index', ['type' => 'products']) }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Товары</a>
                                    @endcan
                                    @can('material')
                                        <a href="{{ route('product.index', ['type' => 'materials']) }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Материалы</a>
                                    @endcan
                                    @can('transport')
                                        <a href="{{ route('transport.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Весь транспорт</a>
                                    @endcan
                                    @can('shift')
                                        <a href="{{ route('transport.shift.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Смены</a>
                                    @endcan
                                    @can('transport_type')
                                        <a href="{{ route('transportType.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Виды ТС</a>
                                    @endcan
                                    @can('delivery')
                                        <a href="{{ route('delivery.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Доставка</a>
                                    @endcan
                                    @can('delivery_price')
                                        <a href="{{ route('shiping_price.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Прайс (доставка)</a>
                                    @endcan
                                    @can('category_product')
                                        <a href="{{ route('category.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Категории товаров</a>
                                    @endcan
                                    @can('error')
                                        <a href="{{ route('errors.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Реестр ошибок</a>
                                    @endcan
                                    @can('error_type')
                                        <a href="{{ route('errorTypes.index') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">справочник ошибок</a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany

                        @canany([
                                'debtor', 'supply', 'order_position','shipment_position',
                                'supply_position', 'operator_order', 'operator_shipment'
                        ])
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Прочее

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- 'Product' sub-menu, show/hide based on menu state. -->
                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">

                                    @can('debtor')
                                        <a href="{{ route('debtors') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.debtors') }}</a>
                                    @endcan
                                    @can('order_position')
                                        <a href="{{ route('order_positions.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции заказов</a>
                                    @endcan
                                    @can('shipment_position')
                                        <a href="{{ route('shipment_products.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции отгрузок</a>
                                    @endcan
                                    @can('operator_order')
                                        <a href="{{ route('operator.orders') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Оператор заказы</a>
                                    @endcan
                                    @can('operator_shipment')
                                        <a href="{{ route('operator.shipments') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Оператор отгрузки</a>
                                    @endcan

                                </div>
                            </div>
                        @endcanany

                        @canany([
                            'amo_order', 'amo_contact', 'contact_link',
                            'double_order', 'call', 'conversation'
                        ])
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    АМО

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                    @can('amo_order')
                                        <a href="{{ route('amo-order.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                                    @endcan
                                    @can('amo_contact')
                                        <a href="{{ route('contactAmo.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Контакты АМО</a>
                                    @endcan
                                    @can('contact_link')
                                        <a href="{{ route('bunch_of_contacts') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Связка контактов</a>
                                    @endcan
                                    @can('double_order')
                                        <a href="{{ route('double_of_orders') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Дубли сделок</a>
                                    @endcan
                                    @can('call')
                                        <a href="{{ route('calls') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Звонки</a>
                                    @endcan
                                    @can('conversation')
                                        <a href="{{ route('conversations') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Беседы</a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany

                        @canany(['user', 'user_permission'])
                                <div class="-mx-3">
                                    <button type="button"
                                            class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                        Пользователи
                                        <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                             aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">
                                        @can('user')
                                            <a href="{{ route('users.all') }}"
                                               class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Пользователи</a>
                                        @endcan
                                        @can('user_permission')
                                            <a href="{{ route('permission') }}"
                                               class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Разрешения</a>
                                        @endcan
                                    </div>
                                </div>
                        @endcanany

                        @can('option')
                            <a href="{{ route('option.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Опции</a>
                        @endcan

                        @canany([
                            'report_manager',
                            'report_manager_two',
                            'report_day',
                            'report_deviation',
                            'report_delivery_category',
                            'report_delivery',
                            'report_transport',
                            'report_transporter',
                            'report_counterparty',
                            'report_summary',
                            'report_summary_remains'
                        ])
                            <div class="-mx-3">
                                <button type="button"
                                    class="menu-toggle flex !flex-row w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                    Сводка

                                    <svg class="menu-icon h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- 'Product' sub-menu, show/hide based on menu state. -->
                                <div class="menu-content mt-2 space-y-2 hidden bg-gray-50 rounded-lg">

                                    @can('report_manager')
                                        <a href="{{ route('manager.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Менеджеры</a>
                                    @endcan

                                    @can('report_manager_two')
                                        <a href="{{ route('manager.managerTwo') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Менеджеры 2</a>
                                    @endcan

                                    @can('report_manager')
                                        <a href="{{ route('manager.managerShipments') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Отгр. по менеджерам</a>
                                    @endcan

                                    @can('report_day')
                                        <a href="{{ route('report.days') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка по дням</a>
                                    @endcan

                                    @can('report_deviation')
                                        <a href="{{ route('report.deviations') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Отклонения</a>
                                    @endcan

                                    @can('report_delivery_category')
                                        <a href="{{ route('report.delivery.category') }}"
                                           class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка по доставке</a>
                                    @endcan

                                    @can('report_delivery')
                                        <a href="{{ route('report.delivery') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Все доставки</a>
                                    @endcan

                                    @can('report_transport')
                                        <a href="{{ route('report.transport') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Транспорт</a>
                                    @endcan

                                    @can('report_transporter')
                                        <a href="{{ route('report.transporter') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Перевозчик</a>
                                    @endcan

                                    @can('report_counterparty')
                                        <a href="{{ route('report.counteparty') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка - Контрагенты</a>
                                    @endcan

                                    @can('report_summary')
                                        <a href="{{ route('summary.index') }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.summary') }}</a>
                                    @endcan
                                    @can('report_summary_remains')
                                        <a href="{{ route('summary.remains') }}"
                                               class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.summaryRemains') }}</a>
                                    @endcan
                                </div>
                            </div>
                        @endcanany


                    </div>
                    <div class="py-6">

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                class="text-base -mx-3 block rounded-lg px-3 py-2.5 font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                                :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <div class="text-base">Выйти</div>
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        const menuToggles = document.querySelectorAll('.menu-toggle');


        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const menuContent = this.nextElementSibling;
                const icon = this.querySelector('.menu-icon');

                menuContent.classList.toggle('hidden');
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);


                icon.classList.toggle('rotate-180');
            });
        });
    </script>

</nav>
