<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <!-- Filters and Controls Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Left Side: Regulator Filter Buttons -->
            <div class="flex items-center space-x-2 border border-gray-300 bg-white rounded-xl p-1">
                @php
                    $allParams = request()->except('regulator');
                    $sebiParams = array_merge(request()->all(), ['regulator' => 'SEBI']);
                    $rbiParams = array_merge(request()->all(), ['regulator' => 'RBI']);
                    $otherParams = array_merge(request()->all(), ['regulator' => 'OTHER']);
                @endphp
                <a href="{{ route('admin.documents.index', $allParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('regulator') ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                        <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                    </svg>
                    All
                </a>
                <a href="{{ route('admin.documents.index', $sebiParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulator') == 'SEBI' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                    </svg>
                    SEBI
                </a>
                <a href="{{ route('admin.documents.index', $rbiParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulator') == 'RBI' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 12h4" />
                        <path d="M10 8h4" />
                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                        <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                    </svg>
                    RBI
                </a>
                <a href="{{ route('admin.documents.index', $otherParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulator') == 'OTHER' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M2 12h20"></path>
                    </svg>
                    OTHER
                </a>
            </div>

            <!-- Right Side: Search, View Toggle, and Filter Icon -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="flex-1 md:flex-initial relative">
                    <div class="relative">
                        <input type="text" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search documents..."
                            class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- View Toggle (Grid/List) -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button id="listViewBtn" onclick="switchView('list')"
                        class="p-2 rounded transition-all duration-200 view-toggle active" title="List View">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-text-align-justify-icon lucide-text-align-justify">
                            <path d="M3 5h18" />
                            <path d="M3 12h18" />
                            <path d="M3 19h18" />
                        </svg>
                    </button>
                    <button id="gridViewBtn" onclick="switchView('grid')"
                        class="p-2 rounded transition-all duration-200 view-toggle" title="Grid View">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-layout-grid-icon lucide-layout-grid">
                            <rect width="7" height="7" x="3" y="3" rx="1" />
                            <rect width="7" height="7" x="14" y="3" rx="1" />
                            <rect width="7" height="7" x="14" y="14" rx="1" />
                            <rect width="7" height="7" x="3" y="14" rx="1" />
                        </svg>
                    </button>
                </div>

                <!-- Filter Icon with Dropdown -->
                <div class="relative">
                    <button id="filterToggle" onclick="toggleFilterDropdown()"
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                    </button>

                    <!-- Filter Dropdown -->
                    <div id="filterDropdown"
                        class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <form method="GET" action="{{ route('admin.documents.index') }}" class="p-6">
                            <!-- Preserve search parameter -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Regulator</label>
                                    <select name="regulator"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All</option>
                                        <option value="SEBI" {{ request('regulator') == 'SEBI' ? 'selected' : '' }}>SEBI
                                        </option>
                                        <option value="RBI" {{ request('regulator') == 'RBI' ? 'selected' : '' }}>RBI
                                        </option>
                                        <option value="OTHER" {{ request('regulator') == 'OTHER' ? 'selected' : '' }}>
                                            OTHER</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Access Type</label>
                                    <select name="access_type"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All</option>
                                        <option value="public" {{ request('access_type') == 'public' ? 'selected' : '' }}>
                                            Public</option>
                                        <option value="restricted" {{ request('access_type') == 'restricted' ? 'selected' : '' }}>Restricted</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit"
                                        class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded transition-colors duration-200 whitespace-nowrap">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('admin.documents.index', request()->only('search')) }}"
                                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-center transition-colors duration-200 whitespace-nowrap">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ZIP Export Button -->
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.export'))
                    <button onclick="openExportModal()"
                        class="flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                            </path>
                        </svg>
                        <span>Export ZIP</span>
                    </button>
                @endif

                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.create'))
                    <a href="{{ route('admin.documents.create') }}"
                        class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-2.5 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Upload Document
                    </a>
                @endif
            </div>
        </div>

        <!-- Documents Table (List View) -->
        <div id="listView" class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Document
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Category
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Regulator
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Access
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Uploaded
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents as $document)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-200">
                                <!-- Document Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 flex items-center justify-center rounded-lg bg-secondary-100">
                                                <svg class="h-6 w-6 text-secondary-500" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="lucide lucide-book-text-icon lucide-book-text">
                                                    <path
                                                        d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                                                    <path d="M8 11h8" />
                                                    <path d="M8 7h6" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($document->file_path)
                                                    @php
                                                        $storage = \Illuminate\Support\Facades\Storage::disk('public');
                                                        $fileExists = $storage->exists($document->file_path);
                                                        $fileSize = $fileExists ? $storage->size($document->file_path) : 0;
                                                        $fileSizeMB = $fileSize > 0 ? number_format($fileSize / 1048576, 2) : '0.00';
                                                        $fileExtension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                                    @endphp
                                                    {{ $fileSizeMB }} MB - {{ strtoupper($fileExtension) }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category Column -->
                                <td class="px-3 py-2">
                                    <div class="text-sm text-gray-900">
                                        {{ $document->category->name }}
                                        @if($document->subCategory)
                                            <span class="text-gray-500"> > {{ $document->subCategory->name }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Regulator Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-secondary-50 text-secondary-500 border border-secondary-200">
                                        @if($document->regulator == 'SEBI')
                                            <!-- SEBI Icon -->
                                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                                </path>
                                            </svg>
                                        @elseif($document->regulator == 'RBI')
                                            <!-- RBI Icon -->
                                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M10 12h4"></path>
                                                <path d="M10 8h4"></path>
                                                <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                                <path
                                                    d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                                </path>
                                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                            </svg>
                                        @elseif($document->regulator == 'OTHER')
                                            <!-- OTHER Icon -->
                                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2v20M2 12h20"></path>
                                            </svg>
                                        @endif
                                        {{ $document->regulator }}
                                    </span>
                                </td>

                                <!-- Access Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($document->isRestricted())
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                            Restricted
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                </path>
                                            </svg>
                                            Public
                                        </span>
                                    @endif
                                </td>

                                <!-- Uploaded Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $document->created_at->format('d M Y') }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Toggle Access Button -->
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.toggle-access'))
                                            <button onclick="toggleAccess({{ $document->id }}, '{{ $document->access_type }}')"
                                                class="text-gray-600 hover:text-gray-900 transition-colors"
                                                title="{{ $document->isRestricted() ? 'Make Public' : 'Make Restricted' }}"
                                                id="access-btn-{{ $document->id }}">
                                                @if($document->isRestricted())
                                                    <!-- Globe icon for Restricted (to make Public) -->
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <!-- Lock icon for Public (to make Restricted) -->
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </button>
                                        @endif
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.edit'))
                                            <a href="{{ route('admin.documents.edit', $document) }}"
                                                class="text-gray-600 hover:text-gray-900 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.delete'))
                                            <button onclick="confirmDelete({{ $document->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No documents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents Grid View -->
        <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $document)
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="h-12 w-12 flex items-center justify-center rounded-lg bg-green-50 flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex space-x-2">
                                <!-- Toggle Access Button -->
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.toggle-access'))
                                    <button onclick="toggleAccess({{ $document->id }}, '{{ $document->access_type }}')"
                                        class="text-gray-600 hover:text-gray-900 transition-colors"
                                        title="{{ $document->isRestricted() ? 'Make Public' : 'Make Restricted' }}"
                                        id="access-btn-grid-{{ $document->id }}">
                                        @if($document->isRestricted())
                                            <!-- Globe icon for Restricted (to make Public) -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                </path>
                                            </svg>
                                        @else
                                            <!-- Lock icon for Public (to make Restricted) -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                        @endif
                                    </button>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.edit'))
                                    <a href="{{ route('admin.documents.edit', $document) }}"
                                        class="text-gray-600 hover:text-gray-900 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.delete'))
                                    <button onclick="confirmDelete({{ $document->id }})"
                                        class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $document->title }}</h3>

                        <div class="space-y-2 mb-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Category:</span>
                                {{ $document->category->name }}
                                @if($document->subCategory)
                                    <span class="text-gray-500"> > {{ $document->subCategory->name }}</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    @if($document->regulator == 'SEBI')
                                        <!-- SEBI Icon -->
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                            </path>
                                        </svg>
                                    @elseif($document->regulator == 'RBI')
                                        <!-- RBI Icon -->
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 12h4"></path>
                                            <path d="M10 8h4"></path>
                                            <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                            <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                            </path>
                                            <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                        </svg>
                                    @elseif($document->regulator == 'OTHER')
                                        <!-- OTHER Icon -->
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v20M2 12h20"></path>
                                        </svg>
                                    @endif
                                    {{ $document->regulator }}
                                </span>

                                @if($document->isRestricted())
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Restricted
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                            </path>
                                        </svg>
                                        Public
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-500">
                                Uploaded: {{ $document->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    No documents found.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Export ZIP Modal -->
    <div id="exportModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeExportModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Select Regulator Bodies for
                                Export</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Select one or more regulator bodies to export
                                    documents:</p>

                                <!-- Select All Checkbox -->
                                <div class="mb-4 pb-3 border-b border-gray-200">
                                    <label
                                        class="flex items-center p-3 border border-blue-300 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors bg-blue-50">
                                        <input type="checkbox" id="selectAllRegulators"
                                            onchange="toggleAllRegulators(this)"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-900">Select All / Deselect
                                                All</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="space-y-3">
                                    <label
                                        class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="export_regulators" value="SEBI"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">SEBI</span>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="export_regulators" value="RBI"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M10 12h4"></path>
                                                <path d="M10 8h4"></path>
                                                <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                                <path
                                                    d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                                </path>
                                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">RBI</span>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="export_regulators" value="OTHER"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M12 2v20M2 12h20"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">OTHER</span>
                                        </div>
                                    </label>
                                </div>

                                <p id="exportError" class="text-sm text-red-600 mt-3 hidden">Please select at least one
                                    regulator body.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="proceedExport()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Export ZIP
                    </button>
                    <button onclick="closeExportModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Document</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this document? This
                                    action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button onclick="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .view-toggle.active {
            background-color: white;
            color: #54b69c;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .view-toggle {
            color: #6b7280;
        }

        .view-toggle:hover {
            color: #374151;
        }
    </style>

    <script>
        // Real-time Search with Debouncing
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                const searchValue = e.target.value;

                // Debounce: wait 500ms after user stops typing
                searchTimeout = setTimeout(function () {
                    const url = new URL(window.location.href);
                    const currentParams = new URLSearchParams(window.location.search);

                    if (searchValue.trim() === '') {
                        currentParams.delete('search');
                    } else {
                        currentParams.set('search', searchValue);
                    }

                    // Preserve other filters
                    const regulator = currentParams.get('regulator') || '';
                    const accessType = currentParams.get('access_type') || '';
                    const dateFrom = currentParams.get('date_from') || '';
                    const dateTo = currentParams.get('date_to') || '';

                    // Build new URL
                    url.search = currentParams.toString();

                    // Navigate to new URL
                    window.location.href = url.toString();
                }, 500);
            });
        }

        // View Toggle Functionality
        function switchView(view) {
            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');

            if (view === 'list') {
                listView.classList.remove('hidden');
                gridView.classList.add('hidden');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
                localStorage.setItem('documentView', 'list');
            } else {
                listView.classList.add('hidden');
                gridView.classList.remove('hidden');
                listBtn.classList.remove('active');
                gridBtn.classList.add('active');
                localStorage.setItem('documentView', 'grid');
            }
        }

        // Load saved view preference
        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('documentView') || 'list';
            switchView(savedView);
        });

        // Filter Dropdown Toggle
        function toggleFilterDropdown() {
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const filterToggle = document.getElementById('filterToggle');
            const filterDropdown = document.getElementById('filterDropdown');

            if (!filterToggle.contains(event.target) && !filterDropdown.contains(event.target)) {
                filterDropdown.classList.add('hidden');
            }
        });

        // Toggle Access Function
        function toggleAccess(documentId, currentAccessType) {
            const button = document.getElementById('access-btn-' + documentId) || document.getElementById('access-btn-grid-' + documentId);
            const isRestricted = currentAccessType === 'restricted';
            const newAccessType = isRestricted ? 'public' : 'restricted';

            // Disable button during request
            if (button) {
                button.disabled = true;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
            }

            fetch('{{ route("admin.documents.toggle-access", ":id") }}'.replace(':id', documentId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    access_type: newAccessType
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showMessage(data.message || 'Access type updated successfully.', 'success');

                        // Reload page after short delay to show updated access type
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        showMessage(data.message || 'Failed to update access type.', 'error');
                        if (button) {
                            button.disabled = false;
                            button.style.opacity = '1';
                            button.style.cursor = 'pointer';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while updating the access type.', 'error');
                    if (button) {
                        button.disabled = false;
                        button.style.opacity = '1';
                        button.style.cursor = 'pointer';
                    }
                });
        }

        // Show message function
        function showMessage(message, type = 'success') {
            const container = document.querySelector('.px-4.py-6');
            if (!container) return;

            const bgColor = type === 'success' ? 'bg-primary-50 border-primary-500 text-primary-800' : 'bg-red-50 border-red-500 text-red-800';
            const iconColor = type === 'success' ? 'text-primary-500' : 'text-red-800';
            const iconPath = type === 'success' ?
                'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' :
                'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';

            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-6 ${bgColor} border-l-4 px-6 py-4 rounded-r-lg shadow-sm`;
            messageDiv.setAttribute('role', 'alert');
            messageDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.insertBefore(messageDiv, container.firstChild);

            // Auto-hide after 3 seconds
            setTimeout(() => {
                messageDiv.style.transition = 'opacity 0.5s';
                messageDiv.style.opacity = '0';
                setTimeout(() => messageDiv.remove(), 500);
            }, 3000);
        }

        // Delete Modal Functions
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = '/admin/documents/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Export Modal Functions
        function openExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
            // Reset checkboxes
            const regulatorCheckboxes = document.querySelectorAll('input[name="export_regulators"]');
            regulatorCheckboxes.forEach(cb => cb.checked = false);
            const selectAllCheckbox = document.getElementById('selectAllRegulators');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            document.getElementById('exportError').classList.add('hidden');

            // Setup event listeners for individual checkboxes
            setupRegulatorCheckboxListeners();
        }

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }

        function toggleAllRegulators(selectAllCheckbox) {
            const isChecked = selectAllCheckbox.checked;
            document.querySelectorAll('input[name="export_regulators"]').forEach(cb => {
                cb.checked = isChecked;
            });
            selectAllCheckbox.indeterminate = false;
            document.getElementById('exportError').classList.add('hidden');
        }

        function setupRegulatorCheckboxListeners() {
            const regulatorCheckboxes = document.querySelectorAll('input[name="export_regulators"]');
            const selectAllCheckbox = document.getElementById('selectAllRegulators');

            if (regulatorCheckboxes.length > 0 && selectAllCheckbox) {
                regulatorCheckboxes.forEach(checkbox => {
                    // Remove existing listener if any
                    checkbox.removeEventListener('change', updateSelectAllCheckbox);
                    // Add new listener
                    checkbox.addEventListener('change', updateSelectAllCheckbox);
                });
            }
        }

        function updateSelectAllCheckbox() {
            const regulatorCheckboxes = document.querySelectorAll('input[name="export_regulators"]');
            const selectAllCheckbox = document.getElementById('selectAllRegulators');

            if (regulatorCheckboxes.length > 0 && selectAllCheckbox) {
                const allChecked = Array.from(regulatorCheckboxes).every(cb => cb.checked);
                const noneChecked = Array.from(regulatorCheckboxes).every(cb => !cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            }
        }

        function proceedExport() {
            const selectedRegulators = Array.from(document.querySelectorAll('input[name="export_regulators"]:checked'))
                .map(cb => cb.value);

            if (selectedRegulators.length === 0) {
                document.getElementById('exportError').classList.remove('hidden');
                return;
            }

            document.getElementById('exportError').classList.add('hidden');

            // Get current filter parameters
            const currentParams = new URLSearchParams(window.location.search);

            // Build export URL with selected regulators
            const exportParams = new URLSearchParams();

            // Preserve other filters
            if (currentParams.get('access_type')) {
                exportParams.set('access_type', currentParams.get('access_type'));
            }
            if (currentParams.get('date_from')) {
                exportParams.set('date_from', currentParams.get('date_from'));
            }
            if (currentParams.get('date_to')) {
                exportParams.set('date_to', currentParams.get('date_to'));
            }
            if (currentParams.get('search')) {
                exportParams.set('search', currentParams.get('search'));
            }

            // Add selected regulators as array
            selectedRegulators.forEach(regulator => {
                exportParams.append('regulators[]', regulator);
            });

            // Close modal first
            closeExportModal();

            // Start download using iframe to avoid page reload
            const exportUrl = '{{ route("admin.documents.export.zip") }}?' + exportParams.toString();

            // Create a hidden iframe to trigger download without page reload
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = exportUrl;
            document.body.appendChild(iframe);

            // Show success message after download starts (with a small delay)
            setTimeout(() => {
                showMessage('Export completed successfully. Your file has been downloaded.', 'success');
                // Remove iframe after download
                setTimeout(() => {
                    iframe.remove();
                }, 1000);
            }, 500);
        }
    </script>
</x-admin-layout>