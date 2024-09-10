<x-app-layout>




    <div class="w-11/12 mx-auto py-8 max-w-10xl">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route('users.managment.store') }}" method="POST">
                    @csrf

                    <div class="min-h-6 p-5">

                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Имя</label>
                            <div class="flex basis-2/3">
                                <input type="text" class="rounded w-full" id="input_name" name="name" placeholder="Administrator">
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_email" class="font-bold flex basis-1/3">Email</label>
                            <div class="flex basis-2/3">
                                <input type="text" class="rounded w-full" id="input_email" name="email" placeholder="example@gmail.com">
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_password" class="font-bold flex basis-1/3">Новый пароль</label>
                            <div class="flex basis-2/3">
                                <input type="text" class="rounded w-full" id="input_password" name="password" placeholder="********">
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_role" class="font-bold flex basis-1/3">Роль</label>
                            <div class="flex basis-2/3">
                                <select class="w-full rounded" name="role" id="input_role">
                                    <option value="admin">Админ</option>
                                    <option value="operator">Оператор</option>
                                    <option value="manager">Менеджер</option>
                                    <option value="dispatcher">Диспетчер</option>
                                    <option value="carrier">Перевозчик</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="p-5 w-full">
                        <button type="submit" class="w-full p-2 bg-green-400 hover:bg-green-600 rounded">{{__("label.create")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
