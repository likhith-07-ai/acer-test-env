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
    <!-- ACER Icons Font -->
    <link rel="stylesheet" href="{{ asset('assets/css/acericons.css') }}">
    <!-- Satoshi Font (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/satoshi.css') }}">
    <!-- Removed Custom Fonts -->
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

        /* Security Features */
        .no-select {
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
        }

        @media print {
            body {
                display: none !important;
            }
        }

        * {
            font-family: 'Satoshi', sans-serif !important;
        }
    </style>
</head>

<body class="no-select" oncontextmenu="return false;" oncopy="return false;" oncut="return false;"
    onpaste="return false;" x-data="{ mobileMenuOpen: false, ratingsMenuOpen: false }">
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
    <div class="min-h-screen">
        <!-- Modern Header Navigation -->
        <!-- Minimal Header Navigation excluded -->

        <!-- Page Content -->
        <main>
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

        <!-- Minimal Footer excluded -->
    </div>
    @stack('scripts')

    <script>
        // Add shadow to header on scroll
        document.addEventListener('DOMContentLoaded', function () {
            const header = document.getElementById('main-header');

            function handleScroll() {
                if (header) {
                    if (window.scrollY > 100) {
                        header.classList.add('sm:shadow-lg');
                    } else {
                        header.classList.remove('sm:shadow-lg');
                    }
                }
            }

            // Check initial scroll position
            handleScroll();

            // Listen for scroll events
            window.addEventListener('scroll', handleScroll);
        });

        // Additional Security
        document.onkeydown = function (e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
            if (e.keyCode == 123) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
            // Disable Print Ctrl+P / Cmd+P
            if ((e.ctrlKey || e.metaKey) && e.keyCode == 'P'.charCodeAt(0)) {
                return false;
            }
        }

        // Block Dragging
        window.ondragstart = function () { return false; } 
    </script>
</body>

</html>