<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8 max-w-10xl">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} №{{ $entityItem->id }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route($action, $entityItem->id) }}" method="post">
                    @csrf
                    @method("PATCH")
                    <div class="min-h-6 p-5">
                        @foreach($columns as $column)
                            @if ($column!="id" and $column!="created_at" and $column!="updated_at")
                                <div class="flex flex-row mb-1">
                                    <label for="input_{{$column}}" class="font-bold flex basis-1/3">{{__("column.".$column)}}</label>
                                    @if (preg_match("/_id\z/u", $column))
                                        @php
                                            $column=substr($column, 0, -3)
                                        @endphp
                                        @if ($entityItem->$column!=null)
                                            <div class="flex basis-2/3">
                                                <input type="text" class="rounded w-full" id="input_{{$column}}" name="{{$column.'_id'}}" placeholder="{{__("column.".$column)}}" value="{{$entityItem->$column->id}}">
                                            </div>
                                        @else
                                            <div class="flex basis-2/3">
                                                <input type="text" class="rounded w-full" id="input_{{$column}}" name="{{$column.'_id'}}" placeholder="{{__("column.".$column)}}" value="{{isset($entityItem->$column->name)?$entityItem->$column->id:''}}">
                                            </div>
                                        @endif
                                    @elseif( $column == 'type' )
                                    <div class="flex basis-2/3">
                                            <select  class="w-full rounded" name="type" id="inputGroupSelect01">
                                                <option value="не выбрано" @if($entityItem->$column == 'не выбрано')selected @endif>не выбрено</option>
                                                <option value="продукция" @if($entityItem->$column == 'продукция') selected @endif>продукция</option>
                                                <option value="материал" @if($entityItem->$column == 'материал')selected @endif>материал</option>
                                            </select>
                                        </div>
                                    @elseif($column == 'building_material')
                                        <div class="flex basis-2/3">
                                            <select  class="w-full rounded" name="building_material" id="inputGroupSelect01">
                                                <option value="не выбрано" @if($entityItem->$column == 'не выбрано')selected @endif>не выбрено</option>
                                                <option value="бетон" @if($entityItem->$column == 'бетон') selected @endif>бетон</option>
                                                <option value="блок" @if($entityItem->$column == 'блок')selected @endif>блок</option>
                                            </select>
                                        </div>
                                    @else
                                        @if(isset($consumption))
                                            <div class="flex basis-2/3">
                                                <input type="text" class="w-full rounded" id="input_{{$column}}" name="{{$column}}" placeholder="{{__("column.".$column)}}" value="{{$consumption}}">
                                            </div>
                                        @endif
                                        <div class="flex basis-2/3">
                                            <input type="text" class="w-full rounded" id="input_{{$column}}" name="{{$column}}" placeholder="{{__("column.".$column)}}" value="{{$entityItem->$column}}">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="p-5 w-full">
                        <button type="submit" class="w-full p-2 bg-green-400 hover:bg-green-600 rounded">{{__("label.save")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
