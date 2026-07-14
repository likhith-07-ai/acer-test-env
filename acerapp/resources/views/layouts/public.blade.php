<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="@yield('meta_description', 'ACER Credit Rating - Empowering investors, issuers, and lenders with reliable credit ratings and in-depth research.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Credit Rating, SEBI, ACER, Financial Markets, India')">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Caladea:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap">
    <!-- ACER Icons Font -->
    <link rel="stylesheet" href="{{ asset('assets/css/acericons.css') }}">
    <!-- Satoshi Font (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/satoshi.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js'])
    @else
        @php
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
            }
        @endphp
        @if(isset($manifest))
            @if(isset($manifest['resources/css/app.css']))
                <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            @endif
            @if(isset($manifest['resources/css/main.css']))
                <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/main.css']['file']) }}">
            @endif
            @if(isset($manifest['resources/js/app.js']))
                <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
            @endif
        @endif
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        /* Prevent FOUC - Hide body until styles are loaded */
        body {
            visibility: hidden;
            opacity: 0;
        }

        body.loaded {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.1s ease-in;
        }
    </style>
</head>

<body class="font-sans antialiased" x-data="{ mobileMenuOpen: false, ratingsMenuOpen: false }">
    <script>
        // Show body immediately after DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('loaded');
        });
        // Fallback: show body after a short delay
        setTimeout(function () {
            document.body.classList.add('loaded');
        }, 100);
    </script>
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[100] bg-primary-500 text-white px-4 py-2 rounded">
        Skip to main content
    </a>
    <div class="min-h-screen">
        <!-- Modern Header Navigation -->
        <header x-data="pressSearch()" id="main-header"
            class="w-full bg-white dark:bg-gray-900 sticky top-0 z-50 transition-all duration-300">
            <div class="cmsContainer">
                <div class="flex justify-between items-center h-16 xl:h-auto">

                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('public.home') }}"
                            class="flex items-center space-x-2 text-gray-900 dark:text-white font-bold text-xl transition-colors duration-200 hover:text-primary-600 dark:hover:text-primary-400">
                            <img src="{{ asset('assets/images/acer/logo.svg') }}"
                                alt="ACER - Accurité Credit & Economic Ratings"
                                class="max-w-full h-[2.5rem] lg:h-[3rem]">
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex">
                        <ul class="lg:flex items-center">
                            <!-- About Link -->
                            <li class="xl:px-4 py-5 xl:py-9 inline-block align-top">
                                <a href="{{ route('public.about') }}"
                                    class="text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 font-bold dark:hover:text-secondary-400 px-4 py-2 text-md transition-all duration-200 rounded-lg {{ request()->routeIs('public.about') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                    About
                                </a>
                            </li>

                            <!-- Ratings Dropdown -->
                            <li class="relative transition-all xl:px-4 py-5 xl:py-9 inline-block align-top"
                                x-data="{ open: false }" @keydown.escape="open = false" @click.outside="open = false">
                                <button type="button" @click="open = !open"
                                    :aria-expanded="open.toString()" aria-haspopup="true" aria-controls="nav-ratings-submenu"
                                    class="text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 font-bold dark:hover:text-secondary-400 px-4 py-2 text-md transition-all duration-200 rounded-lg cursor-pointer {{ request()->routeIs('public.ratings.*') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                    Ratings <i class="acericon-down-angle ml-1 text-xs" aria-hidden="true"></i>
                                </button>
                                <ul id="nav-ratings-submenu" x-show="open" x-cloak role="menu"
                                    class="absolute top-full w-[16.25rem] bg-white dark:bg-gray-800 rounded-br-xl rounded-bl-xl shadow-lg z-[5] overflow-hidden">
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.ratings.index') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.ratings.index') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            Ratings
                                        </a>
                                    </li>
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.ratings.criteria') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.ratings.criteria') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            Rating Criteria
                                        </a>
                                    </li>
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.ratings.process') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.ratings.process') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            Our Rating Process
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Research & Insights Link -->
                            <li class="xl:px-4 py-5 xl:py-9 inline-block align-top">
                                <a href="{{ route('public.research.index') }}"
                                    class="text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 font-bold dark:hover:text-secondary-400 px-4 py-2 text-md transition-all duration-200 rounded-lg {{ request()->routeIs('public.research.*') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                    Research &amp; Insights
                                </a>
                            </li>

                            <!-- Regulator Dropdown -->
                            <li class="relative transition-all xl:px-4 py-5 xl:py-9 inline-block align-top"
                                x-data="{ open: false }" @keydown.escape="open = false" @click.outside="open = false">
                                <button type="button" @click="open = !open"
                                    :aria-expanded="open.toString()" aria-haspopup="true" aria-controls="nav-regulator-submenu"
                                    class="text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 font-bold dark:hover:text-secondary-400 px-4 py-2 text-md transition-all duration-200 rounded-lg cursor-pointer {{ request()->routeIs('public.regulator.*') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                    Regulator <i class="acericon-down-angle ml-1 text-xs" aria-hidden="true"></i>
                                </button>
                                <ul id="nav-regulator-submenu" x-show="open" x-cloak role="menu"
                                    class="absolute top-full w-[16.25rem] bg-white dark:bg-gray-800 rounded-br-xl rounded-bl-xl shadow-lg z-[5] overflow-hidden">
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.regulator.sebi') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.regulator.sebi') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            SEBI
                                        </a>
                                    </li>
                                    @if(config('app.show_rbi_section'))
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.regulator.rbi') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.regulator.rbi') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            RBI
                                        </a>
                                    </li>
                                    @endif
                                    <li class="border-b border-gray-200 dark:border-gray-700 last:border-b-0" role="none">
                                        <a href="{{ route('public.regulator.other-fsr') }}" role="menuitem"
                                            class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold {{ request()->routeIs('public.regulator.other-fsr') ? 'bg-secondary-100 text-secondary-500' : '' }}">
                                            Financial Sector Regulator
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>

                    <!-- Desktop Search & CTA Button -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <!-- Search Trigger Button -->
                        <button @click="isModalOpen = true; $nextTick(() => $refs.modalSearchInput.focus())"
                            type="button"
                            class="text-gray-600 dark:text-gray-300 hover:text-primary-500 transition-colors p-2 min-h-[24px] min-w-[24px] rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
                            aria-label="Search">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <a href="{{ route('public.contact') }}"
                            class="flex flex-row items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-base leading-[1.5] font-medium transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg">
                            Contact Us
                        </a>
                    </div>

                    <!-- Mobile menu button & Search Icon -->
                    <div class="lg:hidden flex items-center gap-1">
                        <button @click="isModalOpen = true; $nextTick(() => $refs.modalSearchInput.focus())"
                            type="button"
                            class="text-gray-700 dark:text-gray-300 hover:text-primary-500 transition-colors p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
                            aria-label="Search">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <button type="button"
                            class="text-gray-700 dark:text-gray-300 hover:text-secondary-500 dark:hover:text-secondary-400 focus:outline-none p-2 rounded-lg transition-colors duration-200"
                            id="mobile-menu-button" aria-expanded="false" aria-label="Toggle navigation menu">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div class="lg:hidden hidden h-[calc(100dvh-4.063rem)]" id="mobile-menu">
                <div
                    class="px-2 pt-2 pb-3 flex flex-col h-full bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 overflow-auto">



                    <a href="{{ route('public.about') }}"
                        class="block px-3 py-3 text-3xl font-bold text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 dark:hover:text-secondary-400 {{ request()->routeIs('public.about') ? 'text-secondary-500' : '' }}">
                        About
                    </a>

                    <!-- Mobile Ratings Dropdown -->
                    <div class="px-3 py-3">
                        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">Ratings</h2>
                        <ul class="pl-4 space-y-1">
                            <li>
                                <a href="{{ route('public.ratings.index') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.ratings.index') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> Ratings
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.ratings.criteria') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.ratings.criteria') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> Rating Criteria
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.ratings.process') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.ratings.process') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> Our Rating Process
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('public.research.index') }}"
                        class="block px-3 py-3 text-3xl font-bold text-quaternary-500 dark:text-gray-300 hover:text-secondary-500 dark:hover:text-secondary-400 {{ request()->routeIs('public.research.*') ? 'text-secondary-500' : '' }}">
                        Research &amp; Insights
                    </a>
                    <!-- Mobile Regulator Dropdown -->
                    <div class="px-3 py-3">
                        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-300 mb-2 font-sans">Regulator</h2>
                        <ul class="pl-4 space-y-1">
                            <li>
                                <a href="{{ route('public.regulator.sebi') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.regulator.sebi') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> SEBI
                                </a>
                            </li>
                            @if(config('app.show_rbi_section'))
                            <li>
                                <a href="{{ route('public.regulator.rbi') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.regulator.rbi') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> RBI
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('public.regulator.other-fsr') }}"
                                    class="block text-gray-600 dark:text-gray-400 hover:text-secondary-500 dark:hover:text-secondary-400 text-2xl font-bold py-1 flex items-center gap-2 {{ request()->routeIs('public.regulator.other-fsr') ? 'text-secondary-500' : '' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8L22 12L18 16"></path>
                                        <path d="M2 12H22"></path>
                                    </svg> Financial Sector Regulator
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="px-3 py-3 mt-auto space-y-3">
                        <a href="{{ route('public.contact') }}"
                            class="w-full flex flex-row items-center justify-center gap-2 px-6 py-4 rounded-xl text-white text-xl leading-[1.5] font-bold transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enhanced Search Modal -->
            <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog"
                aria-modal="true">
                <!-- Dark Overlay Backdrop -->
                <div x-show="isModalOpen" x-transition.opacity
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="isModalOpen = false">
                </div>

                <div class="min-h-screen px-4 text-center flex items-start justify-center pt-20 sm:pt-32 pb-10">
                    <div x-show="isModalOpen" @click.away="isModalOpen = false"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                        class="inline-block w-full max-w-3xl text-left bg-transparent rounded-2xl shadow-2xl relative z-[101] overflow-hidden">

                        <!-- Search Box inside Modal -->
                        <div class="bg-white dark:bg-gray-900 overflow-hidden flex flex-col rounded-2xl">
                            <div
                                class="relative flex items-center p-2 sm:p-4 border-b border-gray-100 dark:border-gray-800">
                                <svg class="h-6 w-6 text-gray-400 ml-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true" focusable="false">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <label for="search-input" class="sr-only">Search press releases</label>
                                <input id="search-input" x-ref="modalSearchInput" x-model="query" type="text"
                                    class="flex-1 w-full pl-4 pr-12 py-3 text-lg bg-transparent border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 placeholder-gray-400"
                                    placeholder="Search press releases..." @keydown.escape="isModalOpen = false">

                                <button type="button" @click="isModalOpen = false"
                                    class="absolute right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-lg p-1.5 min-h-[24px] min-w-[24px] flex items-center justify-center transition-colors">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div x-show="isLoading" aria-live="polite" aria-busy="true"
                                    class="absolute right-14 pr-3 flex items-center pointer-events-none">
                                    <svg class="animate-spin h-5 w-5 text-primary-500" aria-hidden="true"
                                        focusable="false" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Results Container -->
                            <div x-show="query.length > 0" class="max-h-[60vh] overflow-y-auto">
                                <template x-if="results.length > 0">
                                    <div class="py-2">
                                        <div class="px-6 py-2 text-xs font-bold text-gray-400 tracking-wider">
                                            PRESS RELEASES
                                        </div>
                                        <ul class="w-full text-left">
                                            <template x-for="item in results" :key="item.id">
                                                <li>
                                                    <a :href="item.url"
                                                        class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition text-left group">
                                                        <div class="flex items-start justify-between">
                                                            <div class="pr-5">
                                                                <div class="text-base font-bold text-primary-600 dark:text-primary-400 group-hover:text-primary-700 dark:group-hover:text-primary-300 mb-1"
                                                                    x-text="item.company_name"></div>
                                                                <div class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2"
                                                                    x-text="item.headline"></div>
                                                            </div>
                                                            <div
                                                                class="flex-shrink-0 pt-1 text-gray-300 group-hover:text-primary-500 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-gray-400 dark:text-gray-500 mt-2 flex items-center">
                                                            <span x-text="item.date"></span>
                                                        </div>
                                                    </a>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>

                                <template x-if="query.length >= 2 && results.length === 0 && !isLoading">
                                    <div class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">No matching press releases found.
                                        </p>
                                    </div>
                                </template>
                            </div>

                            <!-- Initial state/suggestions when search query is typed but very short -->
                            <template x-if="query.length > 0 && query.length < 2 && !isLoading">
                                <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Keep typing to search...
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main id="main-content" tabindex="-1">
            @if(session('success') && !request()->routeIs('public.contact'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error') && !request()->routeIs('public.contact'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Modern Footer -->
        <footer class="cmsContainer !mb-4 !mt-6">
            <div class="bg-primary-500 text-quinary-50 p-6 md:p-12 rounded-[1.5rem] font-medium">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                    <!-- Left section -->
                    <div class="col-span-1 md:col-span-2 space-y-6 max-w-[20rem]">
                        <div>
                            <a href="{{ route('public.home') }}" title="ACER">
                                <img src="{{ asset('assets/images/acer/footer-logo_68a83bac52644.svg') }}" alt="ACER"
                                    class="h-12">
                            </a>
                        </div>
                        <p class="text-base mb-4">Trusted credit ratings and transparent research for informed financial
                            decisions.</p>
                        <p class="leading-[1.5] text-white font-bold">SEBI-registered credit rating agency</p>

                        <ul class="space-y-2 pt-2 text-base">
                            <li>
                                <a href="{{ route('public.home') }}#office-address-form"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Office Address
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.regulator.sebi') }}#compliance-contact"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Rating Investors&rsquo; Grievance (SEBI)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.regulator.other-fsr') }}#compliance-contact"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Investors&rsquo; Grievance (Other)
                                </a>
                            </li>
                        </ul>

                        <!-- Social icons -->
                        <div class="flex gap-2 pt-4">
                            <div class="w-[2.25rem] h-[2.25rem] flex items-center justify-center">
                                <a href="https://www.linkedin.com/company/acer-credit-rating-pvt-ltd"
                                    class="hover:opacity-80 p-2" rel="noopener nofollow" target="_blank"
                                    aria-label="Follow ACER on LinkedIn (opens in new tab)">
                                    <i class="acericon-linkedin text-[1.25rem]" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-span-1 md:col-span-1">
                        <h3 class="font-bold mb-6 text-[1.25rem] md:text-[1.5rem] leading-[1.3] text-white">Quick Links
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('public.about') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    About
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.ratings.index') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Ratings
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.ratings.criteria') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Rating Criteria
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('public.ratings.process') }}"  
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Understanding Ratings
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ route('public.ratings.process') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Rating Process
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.contact') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Contact
                                </a>
                            </li>
                            <li>
                                <a href="https://scores.sebi.gov.in/" target="_blank" rel="noopener"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    SCORES
                                </a>
                            </li>
                            <li>
                                <a href="https://smartodr.in/login" target="_blank" rel="noopener"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    SMART ODR
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal Links -->
                    <div class="col-span-1 md:col-span-1 md:ml-auto">
                        <h3 class="font-bold mb-6 text-[1.25rem] md:text-[1.5rem] leading-[1.3] text-white">Legal</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('public.regulator.sebi') }}"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Regulator
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.pdf.viewer', ['type' => 'document', 'id' => 26]) }}"
                                    target="_blank"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Code of Conduct
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.pdf.viewer', ['type' => 'document', 'id' => 27]) }}"
                                    target="_blank"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Conflict Policy
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('public.pdf.viewer', ['type' => 'document', 'id' => 39]) }}"
                                    target="_blank"
                                    class="relative inline-block transition-all duration-300 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[0.125rem] after:w-0 after:bg-current after:transition-all after:duration-300 hover:after:w-full">
                                    Withdrawal Policy
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Divider & Copyright -->
                <div
                    class="border-t border-white/40 mt-8 pt-6 md:pt-12 text-sm flex flex-col md:flex-row justify-between items-center gap-4 font-medium">
                    <p class="text-center md:text-left">© <span id="year">{{ date('Y') }}</span> ACER Credit Rating Pvt.
                        Ltd. All rights reserved.</p>
                    <p class="text-center md:text-right max-w-2xl">Disclaimer: Credit ratings are opinions and not
                        recommendations to buy, sell, or hold securities.</p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')

    <script>
        // Add shadow to header on scroll
        document.addEventListener('DOMContentLoaded', function () {
            const header = document.getElementById('main-header');

            function handleScroll() {
                if (window.scrollY > 100) {
                    header.classList.add('sm:shadow-lg');
                } else {
                    header.classList.remove('sm:shadow-lg');
                }
            }

            // Check initial scroll position
            handleScroll();

            // Listen for scroll events
            window.addEventListener('scroll', handleScroll);
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('pressSearch', () => ({
                query: '',
                results: [],
                isLoading: false,
                isOpen: false,
                isModalOpen: false,

                init() {
                    this.$watch('isModalOpen', value => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    this.$watch('query', value => {
                        if (value.length < 2) {
                            this.results = [];
                            this.isOpen = false;
                            return;
                        }
                        this.fetchResults(value);
                    });
                },

                async fetchResults(searchTerm) {
                    this.isLoading = true;
                    // Debounce artificially slightly or rely on fast API
                    try {
                        const response = await fetch(`/api/press-releases/search?q=${encodeURIComponent(searchTerm)}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.results = data;
                            this.isOpen = true;
                        }
                    } catch (error) {
                        console.error('Search failed:', error);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
</body>

</html>