<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 style="font-weight: bold;font-size:32px" class="mb-4 text-red-600">{{__("Çizelgesi Oluşturulmuş Sınıflar")}}</h1>
                    <div class="table w-full border-collapse border border-slate-500">
                        <div class="table-header-group font-bold ">
                            <div class="table-row">
                                <div class="table-cell text-left border border-slate-600 p-2">{{__("Kampüs")}}</div>
                                <div class="table-cell text-left border border-slate-600 p-2">{{__("Şube")}}</div>
                                <div class="table-cell text-left border border-slate-600 p-2">{{__("Düzey")}}</div>
                                <div class="table-cell text-left border border-slate-600 p-2">{{__("Sınıf")}}</div>
                                <div class="table-cell text-left border border-slate-600 p-2 text-center">{{__("Görüntüle")}}</div>
                            </div>
                        </div>
                        @if($timetables->isEmpty())
                            <div class="table-row">
                                <div class="table-cell border border-slate-600 p-2" colspan="5">
                                    <a href="{{ route('timetablecreator.wizard') }}" target="_blank">{{__('Çizelge Oluştur')}}</a>
                                </div>
                            </div>
                        @else
                        <div class="table-row-group">
                            @foreach ($timetables as $timetable)
                            <div class="table-row">
                                <div class="table-cell border border-slate-600 p-2">{{$timetable["campus_name"]}}</div>
                                <div class="table-cell border border-slate-600 p-2">{{$timetable["branch_name"]}}</div>
                                <div class="table-cell border border-slate-600 p-2">{{$timetable["grade_name"]}}</div>
                                <div class="table-cell border border-slate-600 p-2">{{$timetable["classroom_name"]}}</div>
                                <div class="table-cell border border-slate-600 p-2 text-center">
                                    <a href="{{ route('timetable.class', $timetable["classroom_id"] ) }}" target="_blank"><span class="bi bi-eye-fill"></span></a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
