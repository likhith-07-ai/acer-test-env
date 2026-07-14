<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <body class="font-sans text-gray-900 antialiased">
        <script>
            // Show body immediately after DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('loaded');
            });
            // Fallback: show body after a short delay
            setTimeout(function() {
                document.body.classList.add('loaded');
            }, 100);
        </script>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-primary-50 via-white to-secondary-50">
            <div>
                <a href="/">
                    <img src="{{ asset('assets/images/acer/logo.svg') }}" alt="ACER Logo" class="h-16 w-auto">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
