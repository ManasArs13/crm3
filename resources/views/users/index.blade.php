
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
                                    <td class="text-nowrap px-6 py-2">
                                        {{ $user->id }}
                                    </td>
                                    <td class="text-nowrap px-6 py-2">
                                        {{ $user->name }}
                                    </td>
                                    <td class="text-nowrap px-6 py-2">
                                        {{ $user->email }}
                                    </td>
                                    <td class="text-nowrap px-6 py-2">
                                        {{ $user->roles->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="text-nowrap px-6 py-2 flex">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                                    <div class="ms-1">
                                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <div class="py-1" role="none">
                                                    <a href="{{ route('users.managment.edit', $user->id) }}" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1">
                                                        <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 511">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M408.68 1.82615C396.293 5.83515 393.092 8.27415 369.68 31.5402L347.18 53.8991L401.926 108.664L456.672 163.428L477.787 142.433C500.324 120.025 505.515 113.417 508.757 103.01C513.294 88.4511 512.255 75.0461 505.496 60.9381C501.647 52.9041 500.239 51.2542 479.802 30.8382C456.749 7.80815 452.803 4.91815 439.635 1.41315C431.93 -0.63685 415.617 -0.41985 408.68 1.82615ZM173.968 227.189L31.7598 369.438L15.5438 434.438C3.65184 482.102 -0.481161 500.194 0.0438393 502.274C0.942839 505.83 4.69484 509.653 8.13284 510.515C10.8938 511.208 135.804 480.916 140.68 478.371C142.055 477.653 206.743 413.474 284.431 335.75L425.682 194.433L370.929 139.687L316.175 84.9401L173.968 227.189Z" />
                                                        </svg>
                                                        <span>{{ __('label.edit') }}</span>
                                                    </a>
                                                </div>
                                                <div class="py-1" role="none">
                                                    <form action="{{ route('users.managment.destroy', $user->id) }}" method="Post"
                                                          class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full h-full py-2 flex items-center space-x-2">
                                                            <svg class="w-4 h-4 fill-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-red-500">{{ __('label.delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </x-slot>
                                        </x-dropdown>







                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

</x-app-layout>
