<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('lesson-slot.list') }}" class="text-red-500">{{ __('Ders Slotları') }}</a> > {{ __('Ekle') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('lesson-slot.store') }}" method="POST" autocomplete="off" class="mt-4" id="lessonSlotForm">

                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label for="campus_id" class="block text-gray-700">{{ __('Kampüs') }}</x-input-label>
                                <select name="campus_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('campus_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label for="day" class="block text-gray-700">{{ __('Gün') }}</x-input-label>
                                <select name="day" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="1">{{__('Pazartesi')}}</option>
                                    <option value="2">{{__('Salı')}}</option>
                                    <option value="3">{{__('Çarşamba')}}</option>
                                    <option value="4">{{__('Perşembe')}}</option>
                                    <option value="5">{{__('Cuma')}}</option>
                                    <option value="6">{{__('Cumartesi')}}</option>
                                    <option value="7">{{__('Pazar')}}</option>
                                </select>
                                <x-input-error :messages="$errors->get('day')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="start_time" class="block text-gray-700">{{ __('Başlangıç Saati') }}</x-input-label>
                                <x-text-input name="start_time" type="time" id="start_time" class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label for="end_time" class="block text-gray-700">{{ __('Bitiş Saati') }}</x-input-label>
                                <x-text-input name="end_time" type="time" id="end_time" class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>

                        </div>
                        <div class=" pt-3 mt-15 text-gray-700">
                            <div class=" text-right">
                                <div class="text-right">
                                    <button name="submit" type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-sm text-sm w-full">
                                        {{ __('Ekle') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
