<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'CRM' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen max-w-full bg-gray-100">
        <div class="bg-white border-b border-gray-100">
            @if (Route::has('login'))
                <div class="p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Войти</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main>
            <div class="w-11/12 mx-auto py-8">
                @if (isset($entity) && $entity != '')
                    <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
                @endif
                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header --}}
                    <div class="border-b-2 border-neutral-100 overflow-x-auto">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex flex-row gap-1">
                                <div>
                                    @if (url()->current() == route('welcome.index'))
                                        <a href="{{ route('welcome.index') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все
                                            остатки</a>
                                    @else
                                        <a href="{{ route('welcome.index') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все
                                            остатки</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.blocksMaterials'))
                                        <a href="{{ route('welcome.blocksMaterials') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_materials') }}</a>
                                    @else
                                        <a href="{{ route('welcome.blocksMaterials') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_materials') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.blocksCategories'))
                                        <a href="{{ route('welcome.blocksCategories') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_categories') }}</a>
                                    @else
                                        <a href="{{ route('welcome.blocksCategories') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_categories') }}</a>
                                    @endif
                                </div>
                                <div x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                                    @if (url()->current() == route('welcome.blocksProducts'))
                                        <button @click="open = ! open"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            {{ __('column.blocks_products') }} &#9660;
                                        </button>
                                    @else
                                        <button @click="open = ! open"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            {{ __('column.blocks_products') }} &#9660;
                                        </button>
                                    @endif
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-500 mt-2 w-48 rounded-md shadow-lg ltr:origin-top-right rtl:origin-top-left"
                                        style="display: none;">
                                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">

                                            <x-dropdown-link :href="route('welcome.blocksProducts')">
                                                Все товары
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('welcome.blocksProducts', ['type' => 'columns'])">
                                                Колонны
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('welcome.blocksProducts', ['type' => 'covers'])">
                                                Крышки
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('welcome.blocksProducts', ['type' => 'parapets'])">
                                                Парапеты
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('welcome.blocksProducts', ['type' => 'blocks'])">
                                                Блоки
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('welcome.blocksProducts', ['type' => 'dekors'])">
                                                Декор
                                            </x-dropdown-link>

                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.concretesMaterials'))
                                        <a href="{{ route('welcome.concretesMaterials') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.concretes_materials') }}</a>
                                    @else
                                        <a href="{{ route('welcome.concretesMaterials') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.concretes_materials') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.paint'))
                                        <a href="{{ route('welcome.paint') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Потребность
                                            (КРАСКИ)</a>
                                    @else
                                        <a href="{{ route('welcome.paint') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Потребность
                                            (КРАСКИ)</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.processing'))
                                        <a href="{{ route('welcome.processing') }}"
                                            class="block rounded bg-blue-600 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Техоперации
                                            за 5 дней</a>
                                    @else
                                        <a href="{{ route('welcome.processing') }}"
                                            class="block rounded bg-blue-300 px-1 md:px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Техоперации
                                            за 5 дней</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- body --}}
                    @if (url()->current() == route('welcome.processing'))
                        <div class="flex flex-col w-full p-1 bg-white overflow-x-auto">
                            <table class="text-left text-md text-nowrap">
                                <thead>
                                    <tr class="bg-neutral-200 font-semibold">
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.id') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.name') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.date_plan') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.created_at') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.updated_at') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.price') }}
                                        </th>
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.description') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $techchart)
                                        <tr class="border-b-2">
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                <a href="{{ route('techcharts.show', ['techchart' => $techchart->id]) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $techchart->id }}
                                                </a>
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->name }}
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->moment }}
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->created_at }}
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->updated_at }}
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->cost }}
                                            </td>
                                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                                {{ $techchart->description }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="border-b-2 p-3" scope="colspan-4">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @elseif(url()->current() == route('welcome.paint'))
                        <div class="flex flex-col w-full p-1 bg-white overflow-x-auto">
                            <table class="text-xs md:text-base text-nowrap">
                                <thead>
                                    <tr class="bg-neutral-200 font-semibold">

                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'name', 'orderBy' => 'desc']) }}">{{ __('column.name') }}</a>
                                                @if (isset($column) && $column == 'name' && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'name', 'orderBy' => 'asc']) }}">{{ __('column.name') }}</a>
                                                @if (isset($column) && $column == 'name' && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif

                                        <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                            {{ __('column.status_id') }}
                                        </th>

                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual', 'orderBy' => 'desc']) }}">{{ __('column.residual') }}</a>
                                                @if (isset($column) && $column == 'residual' && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual', 'orderBy' => 'asc']) }}">{{ __('column.residual') }}</a>
                                                @if (isset($column) && $column == 'residual' && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif

                                        <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                            {{ __('column.need_from_tc') }}
                                        </th scope="col" class="px-1 md:px-6 py-2 md:py-4">

                                        <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                            {{ __('column.need') }}
                                        </th scope="col" class="px-1 md:px-6 py-2 md:py-4">

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @if ($product->residual_norm)
                                            <tr class="border-b-2">
                                                <th class="text-left px-1 md:px-6 py-2 md:py-4">
                                                    <a
                                                        href="{{ route('product.show', ['product' => $product->id]) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </th>

                                                <th
                                                    class="break-all max-w-32 overflow-hidden px-1 md:px-6 py-2 md:py-4 text-right">
                                                    @if ($product->residual_norm !== 0 && $product->residual_norm !== null)
                                                        <div
                                                            @if (round(($product->residual / $product->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($product->residual / $product->residual_norm) * 100) > 30 &&
                                                                    round(($product->residual / $product->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                                            {{ round(($product->residual / $product->residual_norm) * 100) }}%
                                                        </div>
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                <th class="text-right px-2">
                                                    @if ($product->residual)
                                                        {{ $product->residual }}
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                <th class="text-right px-2">
                                                    @if ($product->need_from_tc)
                                                        {{ $product->need_from_tc }}
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                <th class="text-right px-2">
                                                    @if ($product->residual && $product->need_from_tc)
                                                        @if ($product->residual - $product->need_from_tc < 0)
                                                            {{ abs($product->residual - $product->need_from_tc) }}
                                                        @else
                                                            {{ 0 }}
                                                        @endif
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col w-full p-1 bg-white overflow-x-auto">
                            <table class="text-xs md:text-base text-nowrap">
                                <thead>
                                    <tr class="bg-neutral-200 font-semibold">

                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'name', 'orderBy' => 'desc']) }}">{{ __('column.name') }}</a>
                                                @if (isset($column) && $column == 'name' && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'name', 'orderBy' => 'asc']) }}">{{ __('column.name') }}</a>
                                                @if (isset($column) && $column == 'name' && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif

                                        <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                            {{ __('column.status_id') }}
                                        </th>

                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual_norm', 'orderBy' => 'desc']) }}">{{ __('column.residual_norm') }}</a>
                                                @if (isset($column) && $column == 'residual_norm' && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual_norm', 'orderBy' => 'asc']) }}">{{ __('column.residual_norm') }}</a>
                                                @if (isset($column) && $column == 'residual_norm' && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif

                                        @if (url()->current() == route('residual.blocksProducts') || url()->current() == route('residual.index'))

                                            @if (isset($orderBy) && $orderBy == 'desc')
                                                <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                    <a class="text-black"
                                                        href="{{ route($urlFilter, ['column' => 'materials', 'orderBy' => 'desc']) }}">{{ __('column.materials') }}</a>
                                                    @if (isset($column) && $column == 'materials' && $orderBy == 'desc')
                                                        &#9650;
                                                    @endif
                                                </th>
                                            @else
                                                <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                    <a class="text-black"
                                                        href="{{ route($urlFilter, ['column' => 'materials', 'orderBy' => 'asc']) }}">{{ __('column.materials') }}</a>
                                                    @if (isset($column) && $column == 'materials' && $orderBy == 'asc')
                                                        &#9660;
                                                    @endif
                                                </th>
                                            @endif

                                        @endif

                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual', 'orderBy' => 'desc']) }}">{{ __('column.residual') }}</a>
                                                @if (isset($column) && $column == 'residual' && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                <a class="text-black"
                                                    href="{{ route($urlFilter, ['column' => 'residual', 'orderBy' => 'asc']) }}">{{ __('column.residual') }}</a>
                                                @if (isset($column) && $column == 'residual' && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif

                                        <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                            {{ __('column.need') }}
                                        </th scope="col" class="px-1 md:px-6 py-2 md:py-4">

                                        @if (url()->current() !== route('residual.concretesMaterials') && url()->current() !== route('residual.blocksMaterials'))
                                            <th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                                {{ __('column.making_dais') }}
                                            </th scope="col" class="px-1 md:px-6 py-2 md:py-4">
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @if ($product->residual_norm)
                                            <tr class="border-b-2">
                                                <th class="text-left px-1 md:px-6 py-2 md:py-4">
                                                    <a
                                                        href="{{ route('product.show', ['product' => $product->id]) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </th>

                                                <th
                                                    class="break-all max-w-32 overflow-hidden px-1 md:px-6 py-2 md:py-4">
                                                    @if ($product->residual_norm !== 0 && $product->residual_norm !== null)
                                                        <div
                                                            @if (round(($product->residual / $product->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($product->residual / $product->residual_norm) * 100) > 30 &&
                                                                    round(($product->residual / $product->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                                            {{ round(($product->residual / $product->residual_norm) * 100) }}%
                                                        </div>
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                <th class="text-right">
                                                    @if ($product->residual_norm)
                                                        {{ $product->residual_norm }}
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                @if (url()->current() == route('residual.blocksProducts') || url()->current() == route('residual.index'))
                                                    <th
                                                        class="break-all max-w-32 overflow-hidden px-1 md:px-6 py-2 md:py-4">
                                                        @if ($product->materials == 'нет')
                                                            <div
                                                                class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                                {{ $product->materials }}
                                                            </div>
                                                        @elseif ($product->materials == 'да')
                                                            <div
                                                                class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                                {{ $product->materials }}
                                                            </div>
                                                        @else
                                                            {{ $product->materials }}
                                                        @endif
                                                    </th>
                                                @endif

                                                <th class="text-right px-2">
                                                    @if ($product->residual)
                                                        {{ $product->residual }}
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                <th class="text-right px-2">
                                                    @if ($product->residual && $product->residual_norm)
                                                        @if ($product->residual - $product->residual_norm < 0)
                                                            {{ abs($product->residual - $product->residual_norm) }}
                                                        @else
                                                            {{ 0 }}
                                                        @endif
                                                    @else
                                                        {{ __('column.no') }}
                                                    @endif
                                                </th>

                                                @if (url()->current() !== route('residual.concretesMaterials') && url()->current() !== route('residual.blocksMaterials'))
                                                    <th class="text-right px-2">
                                                        @if ($product->making_day)
                                                            {{ $product->making_day }}
                                                        @else
                                                            @if ($product->residual && $product->residual_norm && $product->release)
                                                                @if ($product->residual - $product->residual_norm >= 0)
                                                                    {{ 0 }}
                                                                @else
                                                                    {{ abs(round(($product->residual - $product->residual_norm) / $product->release, 0)) }}
                                                                @endif
                                                            @else
                                                                {{ 0 }}
                                                            @endif
                                                        @endif
                                                    </th>
                                                @endif

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>

</html>
