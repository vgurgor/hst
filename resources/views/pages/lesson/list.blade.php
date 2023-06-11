<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Ders') }}
                </h2>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('lesson.add') }}">
                    <button type="submit" class="border-solid border-red-500 border-2 rounded-sm bg-white hover:bg-red-500 text-red-500 hover:text-white font-semibold py-2 px-4 rounded-sm text-sm ease-in-out duration-300">
                        {{ __('Ders Ekle') }}
                    </button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="min-height: 70vh;">
                <form action="{{ route('lesson.filter') }}" method="POST" autocomplete="off" class="mt-4">
                    <div class="border-b-2 border-gray-400 pb-3 mb-15 text-gray-700">
                            <div class="flex justify-between items-center">
                                <div class="text-base font-bold text-gray-700 dark:text-gray-200">
                                    <b>{{ __('Filtre(ler)') }}</b>
                                </div>
                                <div class="text-right">
                                    @if(!empty($filter) && $filter)
                                    <a href="{{ route('lesson.list') }}" class="mr-3 text-red-600"><span class="bi bi-x-circle"> </span>Filtreleri temizle</a>
                                    @endif
                                    <button name="submit" type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-sm text-sm">
                                        {{ __('Filtreleri Uygula') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label  for="name" class="block text-gray-700">{{ __('Ders Adı') }}</x-input-label>
                                <x-text-input name="name" type="text" id="name" class="form-input w-full" value="{{ !empty($name) ? $name : '' }}"  />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="major_id" class="block text-gray-700">Branş</x-input-label>
                                <select name="major_id[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" @if(!empty($major_id) && in_array($major->id, $major_id)) selected @endif>{{ $major->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('major_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="grade_id" class="block text-gray-700">Düzey</x-input-label>
                                <select name="grade_id[]" multiple="multiple" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @if(!empty($grade_id) && in_array($grade->id, $grade_id)) selected @endif>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="weekly_frequency" class="block text-gray-700">{{ __('Haftadaki Ağırlık') }}</x-input-label>
                                <x-text-input name="weekly_frequency" type="number" id="weekly_frequency" min="1" value="{{ !empty($weekly_frequency) ? $weekly_frequency : '' }}"  class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('weekly_frequency')" class="mt-2" />
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
                                <th scope="col">{{ __('Ders Adı') }}</th>
                                <th scope="col">{{ __('Branş') }}</th>
                                <th scope="col">{{ __('Düzey') }}</th>
                                <th scope="col">{{ __('Haftadaki Ağırlık') }}</th>
                                <th scope="col">{{ __('Durum') }}</th>
                                <th scope="col" class="text-center w-24">{{ __('İşlemler') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($lessons as $lesson)
                                <tr>
                                    <th scope="row">{{ $lesson->id }}</th>
                                    <td class="text-center py-2">{{ $lesson->name }}</td>
                                    <td class="text-center py-2">{{ $lesson->major->name }}</td>
                                    <td class="text-center py-2">{{ $lesson->grade->name }}</td>
                                    <td class="text-center py-2">{{ $lesson->weekly_frequency }}</td>
                                    <td class="text-center"><span class="bg-{{ $lesson->status == 'active' ? 'green' : 'red' }}-500 text-white py-1 px-2 rounded-full">{{ __($lesson->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('lesson.edit', $lesson->id) }}"><i class="bi bi-pencil text-red-500"></i></a>
                                        <a href="{{ route('lesson.delete', $lesson->id) }}" onclick="event.preventDefault(); document.getElementById('inactive-form-{{ $lesson->id }}').submit();">
                                            <i class="bi bi-trash text-red-500"></i>
                                        </a>
                                        <form id="inactive-form-{{ $lesson->id }}" action="{{ route('lesson.delete', $lesson->id) }}" method="POST" style="display: none;">
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