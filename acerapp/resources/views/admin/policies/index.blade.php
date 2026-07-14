<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Policy Hub</h1>
        </div>

        <!-- Filters and Controls Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Left Side: Status Filter Buttons -->
            <div class="flex items-center space-x-2 border border-gray-300 bg-white rounded-xl p-1">
                @php
                    $allParams = request()->except('status');
                    $draftParams = array_merge(request()->all(), ['status' => 'draft']);
                    $publishedParams = array_merge(request()->all(), ['status' => 'published']);
                @endphp
                <a href="{{ route('admin.policies.index', $allParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('status') ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    All
                </a>
                <a href="{{ route('admin.policies.index', $draftParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('status') == 'draft' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Draft
                </a>
                <a href="{{ route('admin.policies.index', $publishedParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('status') == 'published' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Published
                </a>
            </div>

            <!-- Right Side: Search, Filter, Export -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="flex-1 md:flex-initial relative">
                    <div class="relative">
                        <input type="text" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search policies..."
                            class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
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
                        <form method="GET" action="{{ route('admin.policies.index') }}" class="p-6">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Access Type</label>
                                    <select name="is_restricted"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All</option>
                                        <option value="0" {{ request('is_restricted') === '0' ? 'selected' : '' }}>Public
                                        </option>
                                        <option value="1" {{ request('is_restricted') === '1' ? 'selected' : '' }}>
                                            Restricted</option>
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
                                    <a href="{{ route('admin.policies.index', request()->only('search')) }}"
                                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-center transition-colors duration-200 whitespace-nowrap">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ZIP Export Button -->
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.export'))
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

                <!-- Create Policy Button -->
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.create'))
                    <a href="{{ route('admin.policies.create') }}"
                        class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-2.5 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Create Policy
                    </a>
                @endif
            </div>
        </div>

        <!-- Policies Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Icon
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Title
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Status
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Access Type
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Created On
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($policies as $policy)
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-200">
                                <!-- Icon Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-primary">
                                        @if($policy->icon)
                                            <i class="{{ $policy->icon }} text-xl text-white"></i>
                                        @else
                                            <i class="acericon-doc text-xl text-white"></i>
                                        @endif
                                    </div>
                                </td>

                                <!-- Title Column -->
                                <td class="px-3 py-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $policy->title }}</div>
                                    @if($policy->tagline)
                                        <div class="text-sm text-gray-500 italic">{{ Str::limit($policy->tagline, 50) }}</div>
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($policy->status === 'published')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <!-- Access Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($policy->isRestricted())
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
                                            Public
                                        </span>
                                    @endif
                                </td>

                                <!-- Created On Column -->
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $policy->created_at->format('d M Y') }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-3">
                                        @if($policy->file_path && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.download')))
                                            @php
                                                $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);
                                                $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
                                                $safeTitle = preg_replace('/_+/', '_', $safeTitle);
                                                $safeTitle = trim($safeTitle, '_');
                                                $downloadFileName = $safeTitle . '_' . $policy->id . '.' . $extension;
                                            @endphp
                                            <a href="{{ route('admin.policies.download', $policy) }}"
                                                download="{{ $downloadFileName }}"
                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                title="Download PDF">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.edit'))
                                            <a href="{{ route('admin.policies.edit', $policy) }}"
                                                class="text-gray-600 hover:text-gray-900 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.delete'))
                                            <button onclick="confirmDelete({{ $policy->id }})"
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
                                    No policies found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($policies->hasPages())
            <div class="mt-4">
                {{ $policies->links() }}
            </div>
        @endif
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Select Status for Export</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Select one or more status to export policies:</p>

                                <!-- Select All Checkbox -->
                                <div class="mb-4 pb-3 border-b border-gray-200">
                                    <label
                                        class="flex items-center p-3 border border-blue-300 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors bg-blue-50">
                                        <input type="checkbox" id="selectAllStatus" onchange="toggleAllStatus(this)"
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
                                        <input type="checkbox" name="export_status" value="published"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">Published</span>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="export_status" value="draft"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">Draft</span>
                                        </div>
                                    </label>
                                </div>

                                <p id="exportError" class="text-sm text-red-600 mt-3 hidden">Please select at least one
                                    status.</p>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Policy</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this policy document?
                                    This
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

    <script>
        // Real-time Search with Debouncing
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                const searchValue = e.target.value;

                searchTimeout = setTimeout(function () {
                    const url = new URL(window.location.href);
                    const currentParams = new URLSearchParams(window.location.search);

                    if (searchValue.trim() === '') {
                        currentParams.delete('search');
                    } else {
                        currentParams.set('search', searchValue);
                    }

                    url.search = currentParams.toString();
                    window.location.href = url.toString();
                }, 500);
            });
        }

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

        // Delete Modal Functions
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = '/admin/policies/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Export Modal Functions
        function openExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
            // Reset checkboxes
            const statusCheckboxes = document.querySelectorAll('input[name="export_status"]');
            statusCheckboxes.forEach(cb => cb.checked = false);
            const selectAllCheckbox = document.getElementById('selectAllStatus');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            document.getElementById('exportError').classList.add('hidden');

            // Setup event listeners for individual checkboxes
            setupStatusCheckboxListeners();
        }

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }

        function toggleAllStatus(selectAllCheckbox) {
            const isChecked = selectAllCheckbox.checked;
            document.querySelectorAll('input[name="export_status"]').forEach(cb => {
                cb.checked = isChecked;
            });
            selectAllCheckbox.indeterminate = false;
            document.getElementById('exportError').classList.add('hidden');
        }

        function setupStatusCheckboxListeners() {
            const statusCheckboxes = document.querySelectorAll('input[name="export_status"]');
            const selectAllCheckbox = document.getElementById('selectAllStatus');

            if (statusCheckboxes.length > 0 && selectAllCheckbox) {
                statusCheckboxes.forEach(checkbox => {
                    // Remove existing listener if any
                    checkbox.removeEventListener('change', updateSelectAllStatus);
                    // Add new listener
                    checkbox.addEventListener('change', updateSelectAllStatus);
                });
            }
        }

        function updateSelectAllStatus() {
            const statusCheckboxes = document.querySelectorAll('input[name="export_status"]');
            const selectAllCheckbox = document.getElementById('selectAllStatus');

            if (statusCheckboxes.length > 0 && selectAllCheckbox) {
                const allChecked = Array.from(statusCheckboxes).every(cb => cb.checked);
                const noneChecked = Array.from(statusCheckboxes).every(cb => !cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
            }
        }

        function proceedExport() {
            const selectedStatus = Array.from(document.querySelectorAll('input[name="export_status"]:checked'))
                .map(cb => cb.value);

            if (selectedStatus.length === 0) {
                document.getElementById('exportError').classList.remove('hidden');
                return;
            }

            document.getElementById('exportError').classList.add('hidden');

            // Close modal first
            closeExportModal();

            // Get current filter parameters
            const currentParams = new URLSearchParams(window.location.search);

            // Build export URL with selected status
            const exportParams = new URLSearchParams();

            // Preserve other filters
            if (currentParams.get('is_restricted')) {
                exportParams.set('is_restricted', currentParams.get('is_restricted'));
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

            // Add selected status as array
            selectedStatus.forEach(status => {
                exportParams.append('status[]', status);
            });

            // Start download
            const exportUrl = '{{ route("admin.policies.export.zip") }}?' + exportParams.toString();

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

            // Auto-hide after 5 seconds
            setTimeout(() => {
                messageDiv.style.transition = 'opacity 0.5s';
                messageDiv.style.opacity = '0';
                setTimeout(() => messageDiv.remove(), 500);
            }, 5000);
        }
    </script>
</x-admin-layout>