<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Öğretmen') }}
                </h2>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('teacher.add') }}">
                    <button type="submit" class="border-solid border-red-500 border-2 rounded-sm bg-white hover:bg-red-500 text-red-500 hover:text-white font-semibold py-2 px-4 rounded-sm text-sm ease-in-out duration-300">
                        {{ __('Öğretmen Oluştur') }}
                    </button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="min-height: 70vh;">
                <form action="{{ route('teacher.filter') }}" method="POST" autocomplete="off" class="mt-4">
                    <div class="border-b-2 border-gray-400 pb-3 mb-15 text-gray-700">
                            <div class="flex justify-between items-center">
                                <div class="text-base font-bold text-gray-700 dark:text-gray-200">
                                    <b>{{ __('Filtre(ler)') }}</b>
                                </div>
                                <div class="text-right">
                                    @if(!empty($filter) && $filter)
                                    <a href="{{ route('teacher.list') }}" class="mr-3 text-red-600"><span class="bi bi-x-circle"> </span>Filtreleri temizle</a>
                                    @endif
                                    <button name="submit" type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-sm text-sm">
                                        {{ __('Filtreleri Uygula') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label  for="branch_ids" class="block text-gray-700">Görevli Olduğu Şube(ler)</x-input-label>
                                <select name="branch_ids[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($branches as $key => $value)
                                        <optgroup label="{{$key}}">
                                            @foreach ($value as $id => $branch)
                                                <option value="{{ $id }}" @if(!empty($branch_ids) && in_array($id, $branch_ids)) selected @endif>{{ $branch }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="major_ids" class="block text-gray-700">Branş(lar)</x-input-label>
                                <select name="major_ids[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" @if(!empty($major_ids) && in_array($major->id, $major_ids)) selected @endif>{{ $major->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label for="first_name" class="block text-gray-700">{{ __('Adı') }}</x-input-label>
                                <x-text-input name="first_name" type="text" id="first_name" class="form-input w-full" value="{{ !empty($first_name) ? $first_name : '' }}"  />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label for="last_name" class="block text-gray-700">{{ __('Soyadı') }}</x-input-label>
                                <x-text-input name="last_name" type="text" id="last_name" class="form-input w-full" value="{{ !empty($last_name) ? $last_name : '' }}"  />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="status" class="block text-gray-700">Durum</x-input-label>
                                <select name="status" class="ns w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="" selected>Seçiniz</option>
                                    <option value="active"  @if(!empty($status) && $status == 'active') selected @endif>
                                        {{ __('active') }}</option>
                                    <option value="inactive"  @if(!empty($status) && $status == 'inactive') selected @endif>
                                        {{ __('inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="mt-5">
                        <table class="table-auto w-full">
                            <thead>
                            <tr>
                                <th scope="col" class="w-12">#</th>
                                <th scope="col">{{ __('Adı') }}</th>
                                <th scope="col">{{ __('Soyadı') }}</th>
                                <th scope="col">{{ __('Görevli Olduğu Şube(ler)') }}</th>
                                <th scope="col">{{ __('Branş(lar)') }}</th>
                                <th scope="col">{{ __('Durum') }}</th>
                                <th scope="col" class="text-center w-24">{{ __('İşlemler') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($teachers as $key=>$teacher)
                                <tr>
                                    <th scope="row">{{ $teacher->id }}</th>
                                    <td class="text-center py-2">{{ $teacher->first_name }}</td>
                                    <td class="text-center py-2">{{ $teacher->last_name }}</td>
                                    <td class="text-center py-2">{{$teacher->branches->pluck('name')->implode(', ')}}</td>
                                    <td class="text-center py-2">{{$teacher->majors->pluck('name')->implode(', ')}}</td>
                                    <td class="text-center"><span class="bg-{{ $teacher->status == 'active' ? 'green' : 'red' }}-500 text-white py-1 px-2 rounded-full">{{ __($teacher->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.edit', $teacher->id) }}"><i class="bi bi-pencil text-red-500"></i></a>
                                        <a href="{{ route('teacher.delete', $teacher->id) }}" onclick="event.preventDefault(); document.getElementById('inactive-form-{{ $teacher->id }}').submit();">
                                            <i class="bi bi-trash text-red-500"></i>
                                        </a>
                                        <form id="inactive-form-{{ $teacher->id }}" action="{{ route('teacher.delete', $teacher->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
