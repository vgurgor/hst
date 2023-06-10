<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('teacher.list') }}" class="text-red-500">{{ __('Öğretmen') }}</a> > {{ __('Ekle') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="min-height: 50vh;">
                    <form action="{{ route('teacher.store') }}" method="POST" autocomplete="off" class="mt-4" id="teacherForm">

                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label  for="first_name" class="block text-gray-700">{{ __('Adı') }}</x-input-label>
                                <x-text-input name="first_name" type="text" id="first_name" class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="last_name" class="block text-gray-700">{{ __('Soyadı') }}</x-input-label>
                                <x-text-input name="last_name" type="text" id="last_name" class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="status" class="block text-gray-700">{{ __('Durum') }}</x-input-label>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="active">
                                        {{ __('active') }}</option>
                                    <option value="inactive">
                                        {{ __('inactive') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-full p-3">
                                <x-input-label  for="branch_ids" class="block text-gray-700">Görevli Olduğu Şube(ler)</x-input-label>
                                <select name="branch_ids[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($branches as $key => $value)
                                        <optgroup label="{{$key}}">
                                            @foreach ($value as $id => $branch)
                                                <option value="{{ $id }}" @if(!empty($branch_id) && in_array($id, $branch_id)) selected @endif>{{ $branch }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-full p-3">
                                <x-input-label  for="major_ids" class="block text-gray-700">Branş(lar)</x-input-label>
                                <select name="major_ids[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" @if(!empty($branch_id) && in_array($major->id, $branch_id)) selected @endif>{{ $major->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
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
