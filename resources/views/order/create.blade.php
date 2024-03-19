<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            Добавить {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route($action) }}" method="post">
                    @csrf
                    @method('post')
                    <div class="min-h-6 p-5">

                        <div class="w-full mb-2 flex flex-row gap-3">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Заказ №</span>
                                    <input type="number" min="0"
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary"
                                        placeholder="номер заказа" />
                                </div>
                            </div>
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Статус</span>
                                    <select
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[11%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Контрагент</span>
                            <select
                                class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[11%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Доставка</span>
                            <select
                                class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                @foreach ($deliveries as $delivery)
                                    <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Плановая дата</span>
                            <input type="datetime-local" min="0"
                                class="relative m-0 flex basis-1/5 rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                        </div>


                    </div>

                    <div class="p-5 w-full">
                        <button type="submit"
                            class="w-full p-2 bg-green-400 hover:bg-green-600 rounded">{{ __('label.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
