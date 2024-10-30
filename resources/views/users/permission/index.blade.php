
<x-app-layout>
    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </x-slot>
    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
            </x-slot>
            @endif


            <div class="w-11/12 mx-auto py-8 max-w-10xl">
                @if (isset($entity) && $entity != '')
                    <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
                @endif

                <div class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">


                    {{-- body --}}
                    <div class="flex flex-col rounded-md w-100 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                <td class="px-6 py-3">Разрешения</td>
                                @foreach($roles as $role)
                                    <th scope="col" class="px-6 py-3 max-w-[75px] text-center">{{ $role->name }}</th>
                                @endforeach

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr class="border-b-2">
                                    <td class="text-nowrap px-6 py-2">{{ __('permission.' . $permission->name) }}</td>
                                    @foreach($roles as $role)
                                        <td class="px-6 py-2 max-w-[75px] border-l-2 text-center">
                                            <input
                                                class="rounded-md cursor-pointer role-permission-checkbox"
                                                type="checkbox"
                                                data-role-id="{{ $role->id }}"
                                                data-permission-id="{{ $permission->id }}"
                                                {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                            />
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- header --}}
                        <div class="border-b-2 border-neutral-100">
                            <div class="flex flex-row w-full p-3 justify-between">
                                <div class="flex flex-row gap-1">

                                </div>
                                <div class="flex px-3 text-center font-bold">
                                    <a href="{{ route('users.managment.create') }}"
                                       class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                        Сохранить
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('.role-permission-checkbox').on('change', function () {
                        const roleId = $(this).data('role-id');
                        const permissionId = $(this).data('permission-id');
                        const isChecked = $(this).is(':checked');

                        $.ajax({
                            url: '{{ route("api.permission.update") }}',
                            type: 'POST',
                            data: {
                                role_id: roleId,
                                permission_id: permissionId,
                                assign: isChecked
                            },
                            success: function (response) {
                                console.log(response.message);
                            },
                            error: function (xhr, status, error) {
                                console.error("Error updating permission:", error);
                            }
                        });
                    });
                });
            </script>
</x-app-layout>
