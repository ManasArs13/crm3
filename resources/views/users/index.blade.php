
<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
            </x-slot>
            @endif


            <div class="w-11/12 mx-auto py-8 max-w-10xl">
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
                                    @if (url()->current() == route('users.all'))
                                        <a href="{{ route('users.all') }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Все пользователи</a>
                                    @else
                                        <a href="{{ route('users.all') }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Все пользователи</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('users.roles', ['role' => 'operator']))
                                        <a href="{{ route('users.roles', ['role' => 'operator']) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Операторы</a>
                                    @else
                                        <a href="{{ route('users.roles', ['role' => 'operator']) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Операторы</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('users.roles', ['role' => 'manager']))
                                        <a href="{{ route('users.roles', ['role' => 'manager']) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Менеджеры</a>
                                    @else
                                        <a href="{{ route('users.roles', ['role' => 'manager']) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Менеджеры</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('users.roles', ['role' => 'dispatcher']))
                                        <a href="{{ route('users.roles', ['role' => 'dispatcher']) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Диспетчеры</a>
                                    @else
                                        <a href="{{ route('users.roles', ['role' => 'dispatcher']) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Диспетчеры</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('users.roles', ['role' => 'carrier']))
                                        <a href="{{ route('users.roles', ['role' => 'carrier']) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Перевозчики</a>
                                    @else
                                        <a href="{{ route('users.roles', ['role' => 'carrier']) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Перевозчики</a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route('users.managment.create') }}"
                                   class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                    Добавить
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- body --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                <th scope="col" class="px-6 py-2">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    {{__("column.name")}}
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    {{__("column.login")}}
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    Роль
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    Управление
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                <tr class="border-b-2">
                                    <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                        {{ $user->id }}
                                    </td>
                                    <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                        {{ $user->name }}
                                    </td>
                                    <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                        {{ $user->email }}
                                    </td>
                                    <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                        {{ $user->roles->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                        <a href="{{ route('users.managment.edit', $user->id) }}">
                                            <button class="rounded-lg p-1 font-semibold hover:bg-blue-500 hover:text-white border border-blue-500">

                                                <svg viewBox="0 0 435 512" class="w-6 h-6 hover:fill-white" fill="#2563eb" xmlns="http://www.w3.org/2000/svg">
                                                    <path clip-rule="evenodd" d="M307.215 1.53291C302.943 2.30491 295.968 4.28091 291.715 5.92391C274.271 12.6609 279.976 7.39091 153.185 133.908C85.1194 201.826 33.8414 253.767 31.7214 256.941C29.7044 259.961 27.0224 264.987 25.7614 268.108C24.5004 271.23 18.9114 295.309 13.3414 321.617C1.6754 376.718 1.07339 382.52 5.67139 395.551C8.75639 404.291 14.1954 412.647 20.2954 418.017C28.7034 425.418 43.1554 430.949 54.0854 430.949C57.8804 430.949 143.506 414.403 158.216 410.827C160.837 410.19 166.356 408.085 170.481 406.148C177.849 402.69 180.064 400.546 296.146 284.539C420.819 159.945 419.933 160.892 426.234 145.609C430.802 134.53 432.604 126.027 433.158 112.949C434.026 92.4449 429.146 74.5869 418.204 58.2269C411.069 47.5589 380.866 17.4439 372.481 12.6369C353.06 1.50391 329.523 -2.50109 307.215 1.53291ZM331.047 52.0359C341.353 53.9239 346.984 57.7909 362.721 73.7909C378.405 89.7379 381.514 94.5089 383.46 105.62C384.809 113.326 382.862 123.915 378.827 130.808C377.172 133.636 331.661 180.184 262.341 249.949L148.571 364.449L101.513 372.353C75.6314 376.7 54.2914 380.075 54.0924 379.853C53.8924 379.631 57.8754 358.372 62.9444 332.611L72.1604 285.773L185.571 172.323C284.868 72.9909 299.728 58.5029 304.981 55.8989C314.302 51.2799 321.277 50.2459 331.047 52.0359ZM18.7204 464.284C3.33639 469.863 -1.4456 490.851 9.7594 503.613C11.4274 505.513 14.6344 508.053 16.8864 509.258L20.9814 511.449L214.441 511.709C429.916 511.999 414.557 512.482 422.679 505.159C424.942 503.119 427.613 499.649 428.615 497.449C430.805 492.642 431.049 483.244 429.106 478.594C426.941 473.412 421.443 467.84 415.988 465.3L410.981 462.969L216.481 463.035C69.4314 463.085 21.1854 463.39 18.7204 464.284Z"/>
                                                </svg>
                                            </button>
                                        </a>
                                        <form action="{{ route('users.managment.destroy', $user->id) }}" method="Post" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg p-1 font-semibold hover:bg-red-500 hover:text-white border border-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                     class="w-6 h-6 stroke-red-500 hover:stroke-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>

                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

</x-app-layout>
