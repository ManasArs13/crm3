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
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex flex-row gap-1">
                                <div>
                                    @if (url()->current() == route('welcome.blocksMaterials'))
                                        <a href="{{ route('welcome.blocksMaterials') }}"
                                        class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_materials') }}</a>
                                    @else
                                        <a href="{{ route('welcome.blocksMaterials') }}"
                                        class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_materials') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.blocksCategories'))
                                        <a href="{{ route('welcome.blocksCategories') }}"
                                        class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_categories') }}</a>
                                    @else
                                        <a href="{{ route('welcome.blocksCategories') }}"
                                        class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_categories') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.blocksProducts'))
                                        <a href="{{ route('welcome.blocksProducts') }}"
                                        class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_products') }}</a>
                                    @else
                                        <a href="{{ route('welcome.blocksProducts') }}"
                                        class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.blocks_products') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('welcome.concretesMaterials'))
                                        <a href="{{ route('welcome.concretesMaterials') }}"
                                        class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.concretes_materials') }}</a>
                                    @else
                                        <a href="{{ route('welcome.concretesMaterials') }}"
                                        class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.concretes_materials') }}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex">
                                <div class="card-tools mx-1">
                                    @if (url()->current() == route('welcome.index'))
                                        <a href="{{ route('welcome.index') }}"
                                        class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.all') }}</a>
                                    @else
                                        <a href="{{ route('welcome.index') }}"
                                        class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.all') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>              
                    </div>
        
                     {{-- body --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-md text-nowrap">
                            <thead>
                                <tr class="bg-neutral-200 font-semibold">
                                    <th scope="col" class="px-6 py-4">
                                        {{ __('column.name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        {{ __('column.status_id') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        {{ __('column.residual_norm') }}
                                    </th>
                                    @if (url()->current() == route('residual.blocksProducts') || url()->current() == route('residual.index'))
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.materials') }}
                                        </th>
                                    @endif
                                    <th scope="col" class="px-6 py-4">
                                        {{ __('column.residual') }}
                                    </th scope="col" class="px-6 py-4">
                                    <th scope="col" class="px-6 py-4">
                                        {{ __('column.need') }}
                                    </th>
                                    @if (url()->current() !== route('residual.concretesMaterials') && url()->current() !== route('residual.blocksMaterials'))
                                        <th scope="col" class="px-6 py-4">
                                            {{ __('column.making_dais') }}
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    @if ($product->residual_norm)
                                        <tr class="border-b-2">
                                            <th class="text-left px-6 py-4">
                                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </th>
        
                                            <th class="break-all max-w-32 overflow-hidden px-6 py-4">
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
        
                                            <th>
                                                @if ($product->residual_norm)
                                                    {{ $product->residual_norm }}
                                                @else
                                                    {{ __('column.no') }}
                                                @endif
                                            </th>
        
                                            @if (url()->current() == route('residual.blocksProducts') || url()->current() == route('residual.index'))
                                                <th class="break-all max-w-32 overflow-hidden px-6 py-4">
                                                    @if ($product->materials == 'нет')
                                                        <div class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                            {{ $product->materials }}
                                                        </div>
                                                    @elseif ($product->materials == 'да')
                                                        <div class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                            {{ $product->materials }}
                                                        </div>
                                                    @else
                                                        {{ $product->materials }}
                                                    @endif
                                                </th>
                                            @endif
        
                                            <th>
                                                @if ($product->residual)
                                                    {{ $product->residual }}
                                                @else
                                                    {{ __('column.no') }}
                                                @endif
                                            </th>
        
                                            <th>
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
                                                <th>
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
                </div>
            </div>
        </main>
    </div>
</body>

</html>
