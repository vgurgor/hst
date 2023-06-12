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
                    <h1 style="font-weight: bold;font-size:32px" class="mb-4 text-red-600">{{__("Çizelgeler")}}</h1>
                    <div class="table w-full border-collapse border border-slate-500">
                        <div class="table-header-group font-bold ">
                            <div class="table-row">
                                <div class="table-cell text-left border border-slate-600 p-2">Song</div>
                                <div class="table-cell text-left border border-slate-600 p-2">Artist</div>
                                <div class="table-cell text-left border border-slate-600 p-2">Year</div>
                            </div>
                        </div>
                        <div class="table-row-group">
                            <div class="table-row">
                                <div class="table-cell border border-slate-600 p-2">The Sliding Mr. Bones (Next Stop, Pottersville)</div>
                                <div class="table-cell border border-slate-600 p-2">Malcolm Lockyer</div>
                                <div class="table-cell border border-slate-600 p-2">1961</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell border border-slate-600 p-2">Witchy Woman</div>
                                <div class="table-cell border border-slate-600 p-2">The Eagles</div>
                                <div class="table-cell border border-slate-600 p-2">1972</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell border border-slate-600 p-2">Shining Star</div>
                                <div class="table-cell border border-slate-600 p-2">Earth, Wind, and Fire</div>
                                <div class="table-cell border border-slate-600 p-2">1975</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
