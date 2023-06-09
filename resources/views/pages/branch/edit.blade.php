<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('branch.list') }}" class="text-red-500">{{ __('Şube') }}</a> > {{ __('Düzenle') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('branch.update', $branch->id) }}" method="POST" autocomplete="off" class="mt-4" id="branchForm">
                        @csrf
                        @method('PUT')
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label for="campus_id" class="block text-gray-700">{{ __('Kampüs') }}</x-input-label>
                                <select name="campus_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}" @if($campus->id == $branch->campus_id) selected @endif>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('campus_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="name" class="block text-gray-700">{{ __('Şube Adı') }}</x-input-label>
                                <x-text-input name="name" type="text" id="name" class="form-input w-full"  :value="$branch->name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label for="inputPassword5" class="block text-gray-700">{{ __('Şube Tipi') }}</x-input-label>
                                <select name="type" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($branchTypes as $branchType)
                                        <option value="{{ $branchType->code }}"  @if($branchType->code == $branch->type) selected @endif>{{ __($branchType->name) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="status" class="block text-gray-700">Durum</x-input-label>
                                <select name="status" class="ns w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="active" @if("active" == $branch->status) selected @endif>
                                        {{ __('active') }}</option>
                                    <option value="inactive" @if("inactive" == $branch->status) selected @endif>
                                        {{ __('inactive') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                        <div class=" pt-3 mt-15 text-gray-700">
                            <div class=" text-right">
                                <div class="text-right">
                                    <button name="submit" type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-sm text-sm w-full">
                                        {{ __('Kaydet') }}
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
