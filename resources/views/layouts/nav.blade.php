<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="w-11/12 mx-auto max-w-10xl">
        <div class="flex justify-between h-16">
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex lg:hidden items-center">
                    <a href="{{ route('dashboard') }}" class="uppercase fond-semibold text-indigo-400">
                        crm-euroblock
                    </a>
                </div>

                <!-- main Links -->
                <div class="hidden space-x-3 lg:flex">
                @role('admin|manager|dispatcher')
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


                    <x-nav-link :href="route('residual.index')" :active="request()->routeIs('residual.*')">
                        Остатки
                    </x-nav-link>
                @endrole

                @role('admin')
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

                @role('admin|manager|dispatcher')
                    {{-- Калькулятор --}}
                    <div class="hidden md:flex md:items-center md:ms-1">
                        <x-nav-link :href="route('calculator.block') . '#content-1'">
                            Калькулятор
                        </x-nav-link>
                    </div>
                @endrole

                @role('admin')
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
                    @role('admin|manager|dispatcher')
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

                                @role('admin|manager')
                                <x-dropdown-link :href="route('manager.index')">
                                    Сводка - Менеджеры
                                </x-dropdown-link>
                                @endrole

                                @role('admin')
                                <x-dropdown-link :href="route('report.counteparty')">
                                    Сводка - Контрагенты
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('summary.index')">
                                    {{ __('title.summary') }}
                                </x-dropdown-link>
                                @endrole

                                @role('admin')
                                <x-dropdown-link :href="route('report.transport')">
                                    Сводка - Транспорт
                                </x-dropdown-link>
                                @endrole

                                @role('admin|manager|dispatcher')
                                <x-dropdown-link :href="route('debtors')">
                                    {{ __('title.debtors') }}
                                </x-dropdown-link>
                                @endrole

                                @role('admin')
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







                    @role('admin')
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
                    <x-nav-link :href="route('users.all')" :active="request()->routeIs('users')">
                        Пользователи
                    </x-nav-link>
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
            <div class="-me-2 flex items-center lg:hidden">
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
        @role('admin|manager|dispatcher')
        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('dashboard') }}">Главная</a>
            </div>
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('dashboard-2') }}">Блок</a>
            </div>
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('dashboard-3') }}">Бетон</a>
            </div>
        </div>


        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('order.index') }}">Заказы</a>
            </div>
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('shipment.index') }}">{{ __('title.shipments') }}</a>
            </div>
{{--            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">--}}
{{--                <a href="{{ route('shipment.index2') }}">{{ __('title.shipments2') }}</a>--}}
{{--            </div>--}}
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('residual.index') }}">Остатки</a>
            </div>
        </div>
        @endrole

        @role('operator')
        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('operator.orders') }}">Оператор (Заказы)</a>
            </div>
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('operator.shipments') }}">Оператор (Отгрузки)</a>
            </div>
        </div>
        @endrole

        @role('admin')
        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('product.index', ['type' => 'products']) }}">Товары</a>
            </div>
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('product.index', ['type' => 'materials']) }}">Материалы</a>
            </div>
        </div>

        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('incomings.index') }}">Приход</a>
            </div>
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('outgoings.index') }}">Расход</a>
            </div>
            <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('supply.index') }}">Приёмки</a>
            </div>
        </div>

        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('contact.index') }}">Контакты</a>
            </div>
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('contactAmo.index') }}">Контакты АМО</a>
            </div>
        </div>

        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('techcharts.index') }}">Техкарты</a>
            </div>
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('processings.index') }}">Техоперции</a>
            </div>
        </div>

        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('transport.index') }}">Транспорт</a>
            </div>
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('transportType.index') }}">Виды ТС</a>
            </div>
        </div>
        @endrole

        @role('admin|manager|dispatcher')
        <div class="py-1 px-2 flex flex-row border-b border-gray-200">
            <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                <a href="{{ route('calculator.block') }}">Калькулятор</a>
            </div>
        </div>
        @endrole

        @role('admin')
        <div class="py-1 px-2 flex flex-col border-b border-gray-200">
            <div class="flex flex-row my-1">
                <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                    <a href="{{ route('delivery.index') }}">Доставка</a>
                </div>
                <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                    <a href="{{ route('shiping_price.index') }}">Прайс (доставка)</a>
                </div>
                <div class="basis-1/3 bg-slate-200 text-center mx-1 rounded-sm">
                    <a href="{{ route('category.index') }}">Категории товаров</a>
                </div>
            </div>
            <div class="flex flex-row">
                <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                    <a href="{{ route('order_positions.index') }}">Позиции заказов</a>
                </div>
                <div class="basis-1/2 bg-slate-200 text-center mx-1 rounded-sm">
                    <a href="{{ route('shipment_products.index') }}">Позиции отгрузок</a>
                </div>
            </div>
        </div>
        @endrole

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">

            <div class="py-1 px-2 flex flex-col border-b border-gray-200">
                @role('admin')
                <div class="flex flex-row">
                    <div class="basis-1/2  text-center mx-1 rounded-sm">
                        <a href="{{ route('option.index') }}">Опции</a>
                    </div>
                </div>
                @endrole

                <div class="basis-full text-center mx-auto rounded-sm">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>
