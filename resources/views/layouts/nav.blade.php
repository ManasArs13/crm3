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
                    @role('admin|manager|dispatcher|audit')
                        <x-nav-link :href="route('dashboard-3')" :active="request()->routeIs('dashboard') ||
                            request()->routeIs('dashboard-2') ||
                            request()->routeIs('dashboard-3')">
                            Главная
                        </x-nav-link>

                        <x-nav-link :href="route('order.index')" :active="request()->routeIs('order.*')">
                            Заказы
                        </x-nav-link>

                        <x-nav-link :href="route('shipment.index')" :active="request()->routeIs('shipment.*') && !request()->routeIs('shipment.index2')">
                            {{ __('title.shipments') }}
                        </x-nav-link>


                        <x-nav-link :href="route('residual.blocksCategories')" :active="request()->routeIs('residual.*')">
                            Остатки
                        </x-nav-link>
                    @endrole

                    @role('admin|audit')
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
                                    <x-dropdown-link :href="route('techcharts.index')" :active="request()->routeIs('techcharts.*')">
                                        Техкарты
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('processings.index')" :active="request()->routeIs('processings.*')">
                                        Техоперции
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    @role('admin|manager|dispatcher|audit')
                        {{-- Калькулятор --}}
                        <div class="hidden md:flex md:items-center md:ms-1">
                            <x-nav-link :href="route('calculator.block') . '#content-1'">
                                Калькулятор
                            </x-nav-link>
                        </div>
                    @endrole

                    @role('admin|audit')
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
                                    <x-dropdown-link :href="route('contact.index')">
                                        Контакты
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('product.index', ['type' => 'products'])">
                                        Товары
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('product.index', ['type' => 'materials'])">
                                        Материалы
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('transport.index')">
                                        Весь транспорт
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('transport.shift.index')">
                                        Смены
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('transportType.index')">
                                        Виды ТС
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('delivery.index')">
                                        Доставка
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('shiping_price.index')">
                                        Прайс (доставка)
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('category.index')">
                                        Категории товаров
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole


                    {{-- Прочее --}}
                    @role('admin|manager|dispatcher|audit')
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

                                    @role('admin|audit')
                                        <x-dropdown-link :href="route('incomings.index')">
                                            Приход
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('outgoings.index')">
                                            Расход
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('supply.index')">
                                            Приёмки
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('order_positions.index')">
                                            Позиции заказов
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('shipment_products.index')">
                                            Позиции отгрузок
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('supply_positions.index')">
                                            Позиции приёмок
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('operator.orders')">
                                            Оператор заказы
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('operator.shipments')">
                                            Оператор отгрузки
                                        </x-dropdown-link>
                                    @endrole
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    @role('operator')
                        <x-nav-link :href="route('operator.orders')" :active="request()->routeIs('operator.orders')">
                            Заказы
                        </x-nav-link>

                        <x-nav-link :href="route('operator.shipments')" :active="request()->routeIs('operator.shipments')">
                            {{ __('title.shipments') }}
                        </x-nav-link>
                    @endrole


                    @role('admin|audit')
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
                                    <x-dropdown-link :href="route('amo-order.index')">
                                        Заказы
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('contactAmo.index')">
                                        Контакты АМО
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        @role('admin')
                            <x-nav-link :href="route('users.all')" :active="request()->routeIs('users')">
                                Пользователи
                            </x-nav-link>
                        @endrole
                    @endrole


                    @role('admin|manager|dispatcher|audit')
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

                                    @role('admin|manager|audit')
                                        <x-dropdown-link :href="route('manager.index')">
                                            Сводка - Менеджеры
                                        </x-dropdown-link>
                                    @endrole
                                    @role('admin|manager|dispatcher|audit')
                                        <x-dropdown-link :href="route('report.transport')">
                                            Сводка - Транспорт
                                        </x-dropdown-link>
                                    @endrole

                                    @role('admin|manager|dispatcher|carrier|audit')
                                        <x-dropdown-link :href="route('report.transporter')">
                                            Сводка - Перевозчик
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('report.transporter_fee')">
                                            Сводка - Перевозчик (оплата)
                                        </x-dropdown-link>
                                    @endrole

                                    @role('admin|manager|audit')
                                        <x-dropdown-link :href="route('report.counteparty')">
                                            Сводка - Контрагенты
                                        </x-dropdown-link>
                                    @endrole

                                    @role('admin|audit')
                                        <x-dropdown-link :href="route('summary.index')">
                                            {{ __('title.summary') }}
                                        </x-dropdown-link>
                                    @endrole

                                    @role('admin|audit')
                                        <x-dropdown-link :href="route('summary.remains')">
                                            {{ __('title.summaryRemains') }}
                                        </x-dropdown-link>
                                    @endrole

                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    {{-- Должники --}}
                    @role('admin|manager|dispatcher|audit')
                        @if (request()->get('debtors_balance') < -3000000)
                            <div
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-800 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                <a href="{{ route('debtors') }}"
                                    class="bg-red-300 p-1 rounded-lg">Должники</a>
                            </div>
                        @else
                            <x-nav-link :href="route('debtors')" :active="request()->routeIs('debtors')">
                                Должники
                            </x-nav-link>
                        @endif
                    @endrole

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
                            @role('admin')
                                <x-dropdown-link :href="route('option.index')">
                                    Опции
                                </x-dropdown-link>
                            @endrole

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

                        @role('admin|manager|dispatcher|audit')
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

                            <a href="{{ route('order.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                            <a href="{{ route('shipment.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.shipments') }}</a>
                            <a href="{{ route('residual.blocksCategories') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Остатки</a>
                        @endrole

                        @role('operator')
                            <a href="{{ route('operator.orders') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                            <a href="{{ route('operator.shipments') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Отгрузки</a>
                        @endrole


                        @role('admin|audit')
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
                                    <a href="{{ route('techcharts.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Техкарты</a>
                                    <a href="{{ route('processings.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Техоперации</a>
                                </div>
                            </div>
                        @endrole


                        @role('admin|manager|dispatcher|audit')
                            <a href="{{ route('calculator.block') . '#content-1' }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Калькулятор</a>
                        @endrole


                        @role('admin|audit')
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
                                    <a href="{{ route('contact.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Контакты</a>
                                    <a href="{{ route('product.index', ['type' => 'products']) }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Товары</a>
                                    <a href="{{ route('product.index', ['type' => 'materials']) }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Материалы</a>
                                    <a href="{{ route('transport.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Весь
                                        транспорт</a>
                                    <a href="{{ route('transport.shift.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Смены</a>
                                    <a href="{{ route('transportType.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Виды
                                        ТС</a>
                                    <a href="{{ route('delivery.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Доставка</a>
                                    <a href="{{ route('shiping_price.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Прайс
                                        (доставка)</a>
                                    <a href="{{ route('category.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Категории
                                        товаров</a>
                                </div>
                            </div>
                        @endrole
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

                                @role('admin|manager|dispatcher|audit')
                                    <a href="{{ route('debtors') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.debtors') }}</a>
                                @endrole

                                @role('admin|audit')
                                    <a href="{{ route('incomings.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Приход</a>
                                    <a href="{{ route('outgoings.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Расход</a>
                                    <a href="{{ route('supply.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Приемки</a>
                                    <a href="{{ route('order_positions.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции заказов</a>
                                    <a href="{{ route('shipment_products.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции отгрузок</a>
                                    <a href="{{ route('supply_positions.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Позиции приёмок</a>
                                    <a href="{{ route('operator.orders') }}"
                                       class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Оператор заказы</a>
                                    <a href="{{ route('operator.shipments') }}"
                                       class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Оператор отгрузки</a>
                                @endrole
                            </div>
                        </div>




                        @role('admin|audit')
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
                                    <a href="{{ route('amo-order.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Заказы</a>
                                    <a href="{{ route('contactAmo.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Контакты
                                        АМО</a>
                                </div>
                            </div>
                        @role('admin')
                            <a href="{{ route('users.all') }}"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Пользователи</a>

                            <a href="{{ route('option.index') }}"
                                class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Опции</a>
                        @endrole
                        @endrole

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

                                @role('admin|manager|audit')
                                <a href="{{ route('manager.index') }}"
                                   class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка
                                    - Менеджеры</a>
                                @endrole

                                @role('admin|manager|dispatcher|audit')
                                    <a href="{{ route('report.transport') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка
                                        - Транспорт</a>
                                @endrole

                                @role('admin|manager|dispatcher|carrier|audit')
                                    <a href="{{ route('report.transporter') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка
                                        - Перевозчик</a>
                                    <a href="{{ route('report.transporter_fee') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка
                                        - Перевозчик (оплата)</a>
                                @endrole

                                @role('admin|manager|audit')
                                    <a href="{{ route('report.counteparty') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">Сводка
                                        - Контрагенты</a>
                                @endrole

                                @role('admin|audit')
                                    <a href="{{ route('summary.index') }}"
                                        class="block rounded-lg py-2 pl-6 pr-3 font-semibold leading-7 text-gray-900 hover:bg-gray-50">{{ __('title.summary') }}</a>
                                @endrole
                            </div>
                        </div>


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
