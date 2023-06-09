<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.6.0/dist/multiple-select.min.css">
        <script src="https://unpkg.com/multiple-select@1.6.0/dist/multiple-select.min.js"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/font/bootstrap-icons.css'])

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <div class="fixed bottom-6 right-10">
            <div>
                <input type="checkbox" class="checkbox" id="theme-toggle">
                <label for="theme-toggle" class="checkbox-label">
                    <svg class="switchmoon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"></path>
                    </svg>
                    <svg class="switchsun" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                    </svg>
                    <span class="ball"></span>
                </label>
            </div>
        </div>
        @if(session('success'))
            <div x-data="{ show: true, message: '{{ session('success') }}' }"
                 x-show.transition.duration.500ms="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-10 right-10 bg-green-500 text-white py-2 px-4 rounded-l-lg transition-all ease-in-out duration-1000">
                <p x-text="message"></p>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true, message: '{{ session('error') }}' }"
                 x-show.transition.duration.500ms="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-10 right-10 bg-red-500 text-white py-2 px-4 rounded-l-lg transition-all ease-in-out duration-1000">
                <p x-text="message"></p>
            </div>
        @endif

        <script>
            $("select:not([multiple=multiple])").not(".ns").select2();
            $("select:not([multiple=multiple]).ns").select2({minimumResultsForSearch: -1});
            $("select[multiple=multiple]").not(".ns").multipleSelect({
                filter: true,
                formatSelectAll: function () {
                    return '{{ __('Tümünü seç') }}'
                },
                formatAllSelected: function () {
                    return '{{ __('Tümü seçildi') }}'
                },
                formatCountSelected: function () {
                    return count+ '{{ __(' seçildi') }}'
                },
                formatNoMatchesFound : function () {
                    return '{{ __('Sonuç bulunamadı') }}'
                }

            });
            $("select[multiple=multiple].ns").multipleSelect({
                filter: false,
                formatSelectAll: function () {
                    return '{{ __('Tümünü seç') }}'
                },
                formatAllSelected: function () {
                    return '{{ __('Tümü seçildi') }}'
                },
                formatCountSelected: function () {
                    return count+ '{{ __(' seçildi') }}'
                },
                formatNoMatchesFound : function () {
                    return '{{ __('Sonuç bulunamadı') }}'
                }

            });
        </script>
    </body>
</html>
