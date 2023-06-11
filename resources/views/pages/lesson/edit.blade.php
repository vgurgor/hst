<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="w-1/2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('lesson.list') }}" class="text-red-500">{{ __('Ders') }}</a> > {{ __('Düzenle') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('lesson.update', $lesson->id) }}" method="POST" autocomplete="off" class="mt-4" id="lessonForm">
                        @csrf
                        @method('PUT')
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label  for="major_id" class="block text-gray-700">Branş</x-input-label>
                                <select name="major_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($majors as $major)
                                        <option value="{{ $major->id }}" @if(!empty($lesson->major_id ) && $major->id == $lesson->major_id ) selected @endif>{{ $major->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('major_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="grade_id" class="block text-gray-700">Düzey</x-input-label>
                                <select name="grade_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @if(!empty($lesson->grade_id) && $grade->id == $lesson->grade_id) selected @endif>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="weekly_frequency" class="block text-gray-700">{{ __('Haftadaki Ağırlık') }}</x-input-label>
                                <x-text-input name="weekly_frequency" type="number" id="weekly_frequency" min="1" value="{{$lesson->weekly_frequency}}" class="form-input w-full"   />
                                <x-input-error :messages="$errors->get('weekly_frequency')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 p-3">
                                <x-input-label  for="name" class="block text-gray-700">{{ __('Ders Adı') }}</x-input-label>
                                <x-text-input name="name" type="text" id="name" class="form-input w-full" value="{{$lesson->name}}"  />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="w-1/2 p-3">
                                <x-input-label  for="status" class="block text-gray-700">Durum</x-input-label>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="active" @if($lesson->status == 'active') selected @endif>
                                        {{ __('active') }}</option>
                                    <option value="inactive" @if($lesson->status == 'inactive') selected @endif>
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