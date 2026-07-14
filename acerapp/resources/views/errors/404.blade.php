<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Page Not Found | {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caladea:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap">
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
    <style>
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

<body class="font-sans antialiased">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 100);
    </script>
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <!-- Error Code -->
            <div class="mb-8">
                <h1 class="text-9xl font-bold text-primary-500 mb-4">404</h1>
                <div class="w-24 h-1 bg-primary-500 mx-auto mb-6"></div>
            </div>

            <!-- Error Message -->
            <div class="mb-8">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Page Not Found</h2>
                <p class="text-xl text-gray-600 mb-2">
                    The page you're looking for doesn't exist or has been moved.
                </p>
                <p class="text-lg text-gray-500">
                    Please check the URL or return to the homepage.
                </p>
            </div>

            <!-- Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-blue-100">
                    <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 hover:shadow-lg transition-all duration-300">
                            <i class="acericon-home mr-2"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('public.home') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 hover:shadow-lg transition-all duration-300">
                            <i class="acericon-home mr-2"></i>
                            Go to Home
                        </a>
                    @endif
                @else
                    <a href="{{ route('public.home') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white font-bold rounded-xl hover:bg-primary-600 hover:shadow-lg transition-all duration-300">
                        <i class="acericon-home mr-2"></i>
                        Go to Home
                    </a>
                @endauth
                
                <button onclick="window.history.back()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300">
                    <i class="acericon-left-angle mr-2"></i>
                    Go Back
                </button>
            </div>

            <!-- Quick Links -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-600 font-bold mb-4">You might be looking for:</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('public.home') }}" class="text-primary-500 hover:text-primary-600 font-bold text-sm underline">
                        Home
                    </a>
                    <a href="{{ route('public.about') }}" class="text-primary-500 hover:text-primary-600 font-bold text-sm underline">
                        About
                    </a>
                    <a href="{{ route('public.ratings.index') }}" class="text-primary-500 hover:text-primary-600 font-bold text-sm underline">
                        Ratings
                    </a>
                    <a href="{{ route('public.research.index') }}" class="text-primary-500 hover:text-primary-600 font-bold text-sm underline">
                        Research
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-primary-500 hover:text-primary-600 font-bold text-sm underline">
                        Contact
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

