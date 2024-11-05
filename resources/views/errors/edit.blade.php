<x-app-layout>




    <div class="w-11/12 mx-auto py-8 max-w-10xl">
        @if (session('success'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('success') }}
            </div>
        @endif
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} {{ Auth::user()->name }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route('errors.update', $error->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="min-h-6 p-5">

                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Статус</label>
                            <div class="flex basis-2/3">
                                <select class="rounded w-full" name="status">
                                    <option value="1" @if($error->status == 1) selected @endif>Исправлен</option>
                                    <option value="0" @if($error->status != 1) selected @endif>Не исправлен</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Допущен</label>
                            <div class="flex basis-2/3">
                                <select class="rounded w-full" name="allowed">
                                    <option value="1" @if($error->allowed == 1) selected @endif>Допущен</option>
                                    <option value="0" @if($error->allowed != 1) selected @endif>Не допущен</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Тип</label>
                            <div class="flex basis-2/3">
                                <select class="rounded w-full" name="type_id" disabled>
                                    @foreach($errorTypes as $type)
                                        <option value="{{ $type->id }}" @if($type->id == $error->type_id) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Ответственный</label>
                            <div class="flex basis-2/3">
                                <select class="rounded w-full" name="responsible_user">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == $error->responsible_user) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Ссылка</label>
                            <div class="flex basis-2/3">
                                <input type="text" class="rounded w-full" name="link" value="{{ $error->link }}">
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Комментарий</label>
                            <div class="flex basis-2/3">
                                <input type="text" class="rounded w-full" name="description" value="{{ $error->description }}">
                            </div>
                        </div>
                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Комментарий сотрудника</label>
                            <div class="flex basis-2/3">
                                <textarea class="rounded w-full" cols="30" rows="5" name="user_description">{{ $error->user_description }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="p-5 w-full">
                        <button type="submit" class="w-full p-2 bg-green-400 hover:bg-green-600 rounded">{{__("label.save")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
