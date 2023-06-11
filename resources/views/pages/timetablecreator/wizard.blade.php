<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="height: 70vh">
                    <div class="flex mb-8">
                        <!-- Step Bar -->
                        <div class="w-1/4">
                            <div class="flex justify-center items-center">
                                <div id="stepper-1" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">
                                    1
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{__('Sınıf Seçimi')}}
                            </div>
                        </div>

                        <div class="w-1/4">
                            <div class="flex justify-center items-center">
                                <div id="stepper-2" class="w-8 h-8 rounded-full bg-gray-300 text-white dark:text-white flex items-center justify-center">
                                    2
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{__('Öğretmen Seçimi')}}
                            </div>
                        </div>

                        <div class="w-1/4">
                            <div class="flex justify-center items-center">
                                <div id="stepper-3" class="w-8 h-8 rounded-full bg-gray-300 text-white dark:text-white flex items-center justify-center">
                                    3
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{__('Optimizasyon')}}
                            </div>
                        </div>

                        <div class="w-1/4">
                            <div class="flex justify-center items-center">
                                <div id="stepper-4" class="w-8 h-8 rounded-full bg-gray-300 text-white dark:text-white flex items-center justify-center">
                                    4
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{__('Çıktı')}}
                            </div>
                        </div>
                    </div>
                    <section class="mb-8 " id="step-1">
                        <div class="flex">
                            <div class="w-1/2 p-3 pl-0 mb-4">
                                <label for="campus_id" class="block text-gray-700 dark:text-white">{{__('Kampüs')}}</label>
                                <select id="campus_id" name="campus_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" selected>{{__('Seçiniz')}}</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-1/2 p-3 mb-4">
                                <label for="branch_id" class="block text-gray-700 dark:text-white">{{__('Şube')}}</label>
                                <select id="branch_id" multiple="multiple" name="branch_id[]" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                    <option value="">{{__('Seçiniz')}}</option>
                                </select>
                            </div>

                            <div class="w-1/2 p-3 pr-0 mb-4">
                                <label for="grade_id" class="block text-gray-700 dark:text-white">{{__('Düzey')}}</label>
                                <select id="grade_id" multiple="multiple" name="grade_id[]" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                    <option value="">{{__('Seçiniz')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="classrooms" class="block text-gray-700 dark:text-white">{{__('Çizelge Oluşturulacak Sınıflar')}}</label>
                            <select id="classrooms" name="classrooms[]" multiple="multiple" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="">{{__('Seçiniz')}}</option>
                            </select>
                        </div>

                        <div class="mb-2 w-full text-right">
                            <button type="button" data-to="2" disabled class="disabled:bg-red-300 nextButton bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">{{__('Sonraki')}}</button>
                        </div>

                    </section>

                    <section class="mb-8 hidden" id="step-2">
                        <div class="mb-4">
                            <label for="teachers" class="block text-gray-700 dark:text-white">{{__('Öğretmen(ler)')}}</label>
                            <select id="teachers" name="teachers[]" multiple="multiple" class="w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="">{{__('Seçiniz')}}</option>
                            </select>
                        </div>

                        <div class="flex">
                            <div class="mb-2 w-1/2 text-left">
                                <button type="submit" data-to="1" class="nextButton bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">{{__('Önceki')}}</button>
                            </div>
                            <div class="mb-2 w-1/2 text-right">
                                <button type="submit" data-to="3" class="nextButton bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">{{__('Sonraki')}}</button>
                            </div>
                        </div>
                    </section>

                    <section class="mb-8 hidden" id="step-3">
                        <div class="w-full mb-20">
                            <div class="p-3">
                                <p class="pb-3 mb-4"><span class="p-3 bg-red-500 w-40 text-white">1-</span> {{__('Öncelikle gurobi optimasyonu için gereken data setini indirin')}}</p>
                                <button class="bg-red-500 text-white mx-12 px-4 py-2 rounded-md hover:bg-red-700">{{__('Gurobi Optimiasyon Data Seti')}}</button>
                            </div>
                            <div class="p-3 mt-4">
                                <p><span class="p-3 bg-red-500 w-40 text-white">2-</span> {{__('Optimizasyon sonuç dosyasını yükleyin')}}</p>
                                <div class="py-5 mx-12">
                                    <form action="">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">{{__('Optimizasyon Sonuç Dosyası')}}</label>
                                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file">
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">{{__('Sadece .txt uzantılı dosyalar')}}</p>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mb-2 w-1/2 text-left">
                                <button type="submit" data-to="2" class="nextButton bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-700">{{__('Önceki')}}</button>
                            </div>
                            <div class="mb-2 w-1/2 text-right">
                                <button type="submit" data-to="3" class="nextButton bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">{{__('Sonraki')}}</button>
                            </div>
                        </div>
                    </section>
                    <div class="hidden bg-yellow-300 px-3 py-2 border-2 border-yellow-500">
                        <h1 class="text-lg font-bold text-gray-500"><i class="bi bi-exclamation-triangle-fill"></i> {{__("Gereksinim hatası")}}</h1>
                        <div id="error-box"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/pages/timetablecreator.wizard.js'])
</x-app-layout>
