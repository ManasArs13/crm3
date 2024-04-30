<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="w-11/12 mx-auto px-4 sm:px-6 lg:px-8">
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

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') ||
                        request()->routeIs('dashboard-2') ||
                        request()->routeIs('dashboard-3')">
                        Главная
                    </x-nav-link>


                    <x-nav-link :href="route('order.index')" :active="request()->routeIs('order.*')">
                        Заказы
                    </x-nav-link>

                    <x-nav-link :href="route('shipment.index')" :active="request()->routeIs('shipment.*')">
                        Отгрузки
                    </x-nav-link>

                    

                    <x-nav-link :href="route('residual.index')" :active="request()->routeIs('residual.*')">
                        Остатки
                    </x-nav-link>

                    <x-nav-link :href="route('product.index', ['type' => 'products'])" :active="request()->routeIs('product.*') && request()->type == 'products'">
                        Товары
                    </x-nav-link>

                    <x-nav-link :href="route('product.index', ['type' => 'materials'])" :active="request()->routeIs('product.*') && request()->type == 'materials'">
                        Материалы
                    </x-nav-link>

                    {{-- Приход - расход --}}
                    <div class="hidden md:flex md:items-center md:ms-1">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Приход/Расход</div>

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
                                <x-dropdown-link :href="route('incomings.index')">
                                    Приход
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('outgoings.index')">
                                    Расход
                                </x-dropdown-link>
                                
                                <x-dropdown-link  :href="route('supplies.index')">
                                    Приёмки
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Контакты --}}
                    <div class="hidden md:flex md:items-center md:ms-1">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Контакты</div>

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

                                <x-dropdown-link :href="route('contactAmo.index')">
                                    Контакты АМО
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

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

                    {{-- Транспорт --}}
                    <div class="hidden md:flex md:items-center md:ms-1">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Транспорт</div>

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
                                <x-dropdown-link :href="route('transport.index')">
                                    Весь транспорт
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('transportType.index')">
                                    Виды ТС
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Калькулятор --}}
                    <div class="hidden md:flex md:items-center md:ms-1">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Калькулятор</div>

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
                                <x-dropdown-link :href="route('calculator.block')">
                                    Расчёт - БЛОК
                                </x-dropdown-link>

                                {{-- <x-dropdown-link :href="route('calculator.concrete')">
                                    Расчёт - БЕТОН
                                </x-dropdown-link> --}}
                            </x-slot>
                        </x-dropdown>
                    </div>

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
                                <x-dropdown-link :href="route('delivery.index')">
                                    Доставка
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('shiping_price.index')">
                                    Прайс (доставка)
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('category.index')">
                                    Категории товаров
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('order_positions.index')">
                                    Позиции заказов
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('shipment_products.index')">
                                    Позиции отгрузок
                                </x-dropdown-link>
                                
                            </x-slot>
                        </x-dropdown>
                    </div>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

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

                        <x-dropdown-link :href="route('option.index')">
                            Опции
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

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
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                <x-dropdown-link :href="route('option.index')">
                    Опции
                </x-dropdown-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
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
</nav>
