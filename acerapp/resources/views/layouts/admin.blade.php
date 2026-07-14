<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- ACER Icons Font -->
    <link rel="stylesheet" href="{{ asset('assets/css/acericons.css') }}">
    <!-- Satoshi Font (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/css/satoshi.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            @if(isset($manifest['resources/js/app.js']))
                <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
            @endif
        @endif
    @endif
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
    <style>
        /* Sidebar Styles - Minimal & Modern */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            margin: 0.125rem 0.5rem;
            color: #6b7280;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .sidebar-link:hover {
            color: #3aafa9;
        }

        .sidebar-link:focus {
            outline: none;
        }

        .sidebar-link.active {
            color: #3aafa9;
            font-weight: 500;
        }

        .sidebar-link.active:hover {
            color: #43927d;
        }

        .sidebar-link svg {
            flex-shrink: 0;
            color: inherit;
            transition: color 0.15s ease;
        }

        /* Logo Section */
        .sidebar-logo {
            transition: none;
        }

        .sidebar-logo:hover {
            transform: none;
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            z-index: 50;
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown.active .dropdown-content {
            display: block;
        }

        /* Sidebar Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #f9fafb;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

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

<body class="font-sans antialiased bg-[#f9fafb]">
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
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-200 shadow-sm flex flex-col relative">
            <!-- Logo Section -->
            <div class="px-6 py-4 border-b border-gray-200 bg-white flex items-center" style="height: 73px;">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-logo flex items-center justify-center group w-full">
                    <img src="{{ asset('assets/images/acer/logo.svg') }}" alt="ACER Logo" class="h-12 w-auto">
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 py-6 sidebar-nav overflow-y-auto">
                <div class="px-2">
                    <div class="mb-4 px-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Main Menu</p>
                    </div>

                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('dashboard.view'))
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span class="flex-1">Dashboard</span>
                        </a>
                    @endif

                    <!-- Documents Dropdown -->
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasAnyPermission(['documents.view', 'doc-categories.view']))
                        <div class="mb-1">
                            <button onclick="toggleDropdown('documents-menu')"
                                class="sidebar-link w-full flex items-center justify-between {{ request()->routeIs('admin.documents.*') || request()->routeIs('admin.doc-categories.*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="flex-1">Documents</span>
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-150" id="documents-menu-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="documents-menu" class="hidden pl-8 mt-1 space-y-0.5">
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.view'))
                                    <a href="{{ route('admin.documents.index') }}"
                                        class="block px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.documents.*') && !request()->routeIs('admin.doc-categories.*') ? 'text-[#3aafa9]' : 'text-gray-600 hover:text-[#3aafa9]' }}">
                                        All Documents
                                    </a>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.view'))
                                    <a href="{{ route('admin.doc-categories.index') }}"
                                        class="block px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.doc-categories.*') ? 'text-[#3aafa9]' : 'text-gray-600 hover:text-[#3aafa9]' }}">
                                        Categories
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Research Articles Dropdown - HIDDEN -->
                    @if(
                            auth()->user()->isSuperAdmin() ||
                            auth()->user()->hasAnyPermission([
                                'research-articles.view',
                                'research-categories.view',
                                'research-tags.view'
                            ])
                        )
                        <div class="mb-1">
                            <button onclick="toggleDropdown('research-menu')"
                                class="sidebar-link w-full flex items-center justify-between {{ request()->routeIs('admin.research-articles.*') || request()->routeIs('admin.research-categories.*') || request()->routeIs('admin.research-tags.*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                    <span class="flex-1">Research Articles</span>
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-150" id="research-menu-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="research-menu" class="hidden pl-8 mt-1 space-y-0.5">
                                @if(
                                        auth()->user()->isSuperAdmin() ||
                                        auth()->user()->hasPermission('research-articles.view')
                                    )
                                    <a href="{{ route('admin.research-articles.index') }}"
                                        class="block px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.research-articles.*') && !request()->routeIs('admin.research-categories.*') && !request()->routeIs('admin.research-tags.*') ? 'text-[#3aafa9]' : 'text-gray-600 hover:text-[#3aafa9]' }}">
                                        All Articles
                                    </a>
                                @endif
                                @if(
                                        auth()->user()->isSuperAdmin() ||
                                        auth()->user()->hasPermission('research-categories.view')
                                    )
                                    <a href="{{ route('admin.research-categories.index') }}"
                                        class="block px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.research-categories.*') ? 'text-[#3aafa9]' : 'text-gray-600 hover:text-[#3aafa9]' }}">
                                        Categories
                                    </a>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.view'))
                                    <a href="{{ route('admin.research-tags.index') }}"
                                        class="block px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.research-tags.*') ? 'text-[#3aafa9]' : 'text-gray-600 hover:text-[#3aafa9]' }}">
                                        Tags
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Policy Hub -->
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.view'))
                        <a href="{{ route('admin.policies.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.policies.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="flex-1">Policy Hub</span>
                        </a>
                    @endif

                    <!-- Press Releases -->
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('press-releases.view'))
                        <a href="{{ route('admin.press-releases.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.press-releases.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                            <span class="flex-1">Press Releases</span>
                        </a>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="flex-1">Users</span>
                        </a>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.roles.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <span class="flex-1">Roles & Permissions</span>
                        </a>
                    @endif

                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('audit-logs.view'))
                        <a href="{{ route('admin.audit-logs.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="flex-1">Activity Logs</span>
                        </a>
                    @endif
                </div>
            </nav>

            <!-- Footer in Sidebar -->
            <div class="px-2 py-4">
                <a href="{{ route('public.regulator.sebi') }}" target="_blank" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <span class="flex-1">Public View</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200" style="height: 73px;">
                <div class="px-8 h-full flex justify-between items-center">
                    <!-- Page Title (Left Side) -->
                    <div>
                        <h1 class="text-2xl font-bold text-quaternary-700">
                            @if(request()->routeIs('admin.dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('admin.documents.*'))
                                Documents Management
                            @elseif(request()->routeIs('admin.categories.*'))
                                Categories Management
                            @elseif(request()->routeIs('admin.research-articles.*'))
                                Research Articles Management
                            @elseif(request()->routeIs('admin.policies.*'))
                                Policy Hub Management
                            @elseif(request()->routeIs('admin.users.*'))
                                Users Management
                            @elseif(request()->routeIs('admin.roles.*'))
                                Roles & Permissions Management
                            @elseif(request()->routeIs('admin.audit-logs.*'))
                                Activity Logs
                            @else
                                Admin Panel
                            @endif
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">Regulatory Disclosures Management System</p>
                    </div>

                    <!-- User Dropdown (Right Side) -->
                    <div class="flex items-center space-x-4">
                        <div class="dropdown relative">
                            <button
                                class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-quaternary-700">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Administrator</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="dropdown-content">
                                <div class="py-2">
                                    <a href="{{ route('admin.profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Profile Settings
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto flex flex-col">
                <div class="{{ $mainInnerWrapper ?? 'px-8 py-6' }} flex-1">
                    @if(session('success'))
                        <div class="mb-6 bg-primary-50 border-l-4 border-primary-500 text-primary-800 px-6 py-4 rounded-r-lg shadow-sm"
                            role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm"
                            role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Hide general error display to avoid duplicates with field-specific errors --}}
                    {{-- Field-specific errors are already shown below their respective fields using @error directive
                    --}}
                    @if ($errors->any() && !request()->routeIs('admin.doc-categories.*'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-r-lg shadow-sm"
                            role="alert">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li class="font-medium">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        // Dropdown toggle functionality for sidebar menus
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(menuId + '-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                menu.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-expand dropdowns if current route matches
        document.addEventListener('DOMContentLoaded', function () {
            @if(request()->routeIs('admin.documents.*') || request()->routeIs('admin.doc-categories.*'))
                const documentsMenu = document.getElementById('documents-menu');
                const documentsIcon = document.getElementById('documents-menu-icon');
                if (documentsMenu) {
                    documentsMenu.classList.remove('hidden');
                    if (documentsIcon) documentsIcon.style.transform = 'rotate(180deg)';
                }
            @endif

                @if(request()->routeIs('admin.research-articles.*') || request()->routeIs('admin.research-categories.*') || request()->routeIs('admin.research-tags.*'))
                    const researchMenu = document.getElementById('research-menu');
                    const researchIcon = document.getElementById('research-menu-icon');
                    if (researchMenu) {
                        researchMenu.classList.remove('hidden');
                        if (researchIcon) researchIcon.style.transform = 'rotate(180deg)';
                    }
                @endif

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.mb-1')) {
                    // Keep dropdowns open if they contain active links
                }
            });
        });

        // Original dropdown toggle functionality
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.querySelector('.dropdown');
            if (dropdown) {
                const dropdownButton = dropdown.querySelector('button');

                dropdownButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('active');
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>