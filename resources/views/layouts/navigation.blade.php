<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:flex sm:items-center ">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-dropdown align="left">
                        <x-slot name="trigger" >
                            <x-nav-link href="#" :active="request()->routeIs('campus.list') || request()->routeIs('campus.filter') || request()->routeIs('campus.add')  || request()->routeIs('campus.edit') || request()->routeIs('branch.list') || request()->routeIs('branch.filter') || request()->routeIs('branch.add')  || request()->routeIs('branch.edit') || request()->routeIs('grade.list') || request()->routeIs('grade.filter') || request()->routeIs('grade.add')  || request()->routeIs('grade.edit') ||request()->routeIs('classroom.list') || request()->routeIs('classroom.filter') || request()->routeIs('classroom.add')  || request()->routeIs('classroom.edit') || request()->routeIs('major.list') || request()->routeIs('major.filter') || request()->routeIs('major.add')  || request()->routeIs('major.edit')">
                                {{ __('Okul Yönetimi') }}
                            </x-nav-link>
                        </x-slot>

                        <x-slot name="content" >
                            <x-dropdown-link :href="route('campus.list')" :active="request()->routeIs('campus.list') || request()->routeIs('campus.filter') || request()->routeIs('campus.add')  || request()->routeIs('campus.edit')">
                                {{ __('Kampüs') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('branch.list')" :active="request()->routeIs('branch.list') || request()->routeIs('branch.filter') || request()->routeIs('branch.add')  || request()->routeIs('branch.edit')">
                                {{ __('Şube') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('grade.list')" :active="request()->routeIs('grade.list') || request()->routeIs('grade.filter') || request()->routeIs('grade.add')  || request()->routeIs('grade.edit')">
                                {{ __('Düzey') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('classroom.list')" :active="request()->routeIs('classroom.list') || request()->routeIs('classroom.filter') || request()->routeIs('classroom.add')  || request()->routeIs('classroom.edit')">
                                {{ __('Sınıf') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('major.list')" :active="request()->routeIs('major.list') || request()->routeIs('major.filter') || request()->routeIs('major.add')  || request()->routeIs('major.edit')">
                                {{ __('Branş') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="left">
                        <x-slot name="trigger">
                            <x-nav-link href="#" :active="request()->routeIs('teacher.list') || request()->routeIs('teacher.filter') || request()->routeIs('teacher.add')  || request()->routeIs('teacher.edit')">
                                {{ __('Öğretmen Yönetimi') }}
                            </x-nav-link>
                        </x-slot>

                        <x-slot name="content" >
                            <x-dropdown-link :href="route('teacher.list')" :active="request()->routeIs('teacher.list') || request()->routeIs('teacher.filter') || request()->routeIs('teacher.add')  || request()->routeIs('teacher.edit')">
                                {{ __('Öğretmen') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="left">
                        <x-slot name="trigger">
                            <x-nav-link href="#" :active="request()->routeIs('lesson.list') || request()->routeIs('lesson.filter') || request()->routeIs('lesson.add')  || request()->routeIs('lesson.edit')  || request()->routeIs('lesson-slot.list') || request()->routeIs('lesson-slot.filter') || request()->routeIs('lesson-slot.add')  || request()->routeIs('lesson-slot.edit') ">
                                {{ __('Ders Yönetimi') }}
                            </x-nav-link>
                        </x-slot>

                        <x-slot name="content" >
                            <x-dropdown-link :href="route('lesson.list')" :active="request()->routeIs('lesson.list') || request()->routeIs('lesson.filter') || request()->routeIs('lesson.add')  || request()->routeIs('lesson.edit')">
                                {{ __('Ders') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('lesson-slot.list')" :active="request()->routeIs('lesson-slot.list') || request()->routeIs('lesson-slot.filter') || request()->routeIs('lesson-slot.add')  || request()->routeIs('lesson-slot.edit')">
                                {{ __('Ders Slotları') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <x-nav-link :href="route('timetablecreator.wizard')" :active="request()->routeIs('timetablecreator.wizard')">
                        {{ __('Çizelge Oluşturucu') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                @if (app()->getLocale() === 'tr')
                    <a class="text-red-600" href="{{ route('changeLang', 'en') }}" style="width: 32px; height: 24px">
                        <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-gb" viewBox="0 0 640 480">
                            <path fill="#012169" d="M0 0h640v480H0z"/>
                            <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/>
                            <path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L54 480H0l240-179zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/>
                            <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                            <path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/>
                        </svg>
                    </a>
                @else
                    <a class="text-red-600" href="{{ route('changeLang', 'tr') }}" style="width: 32px; height: 24px">

                        <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-tr" viewBox="0 0 640 480">
                            <g fill-rule="evenodd">
                                <path fill="#e30a17" d="M0 0h640v480H0z"/>
                                <path fill="#fff" d="M407 247.5c0 66.2-54.6 119.9-122 119.9s-122-53.7-122-120 54.6-119.8 122-119.8 122 53.7 122 119.9z"/>
                                <path fill="#e30a17" d="M413 247.5c0 53-43.6 95.9-97.5 95.9s-97.6-43-97.6-96 43.7-95.8 97.6-95.8 97.6 42.9 97.6 95.9z"/>
                                <path fill="#fff" d="m430.7 191.5-1 44.3-41.3 11.2 40.8 14.5-1 40.7 26.5-31.8 40.2 14-23.2-34.1 28.3-33.9-43.5 12-25.8-37z"/>
                            </g>
                        </svg>
                    </a>
                @endif
            </div>


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
