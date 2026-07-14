<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Document Categories</h1>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.create'))
            <a href="{{ route('admin.doc-categories.create') }}"
                class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-2.5 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Add Category
            </a>
            @endif
        </div>

        <!-- Filters and Controls Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Left Side: Regulatory Body Filter Buttons -->
            <div class="flex items-center space-x-2 border border-gray-300 bg-white rounded-xl p-1">
                @php
                $allParams = request()->except('regulatory_body');
                $sebiParams = array_merge(request()->all(), ['regulatory_body' => 'SEBI']);
                $rbiParams = array_merge(request()->all(), ['regulatory_body' => 'RBI']);
                $otherParams = array_merge(request()->all(), ['regulatory_body' => 'OTHER']);
                @endphp
                <a href="{{ route('admin.doc-categories.index', $allParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('regulatory_body') ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    All
                </a>
                <a href="{{ route('admin.doc-categories.index', $sebiParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulatory_body') == 'SEBI' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                    </svg>
                    SEBI
                </a>
                <a href="{{ route('admin.doc-categories.index', $rbiParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulatory_body') == 'RBI' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 12h4"></path>
                        <path d="M10 8h4"></path>
                        <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                        <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                    </svg>
                    RBI
                </a>
                <a href="{{ route('admin.doc-categories.index', $otherParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('regulatory_body') == 'OTHER' ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M2 12h20"></path>
                    </svg>
                    OTHER
                </a>
            </div>

            <!-- Right Side: Search and Filter Icon -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="flex-1 md:flex-initial relative">
                    <div class="relative">
                        <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search categories..."
                            class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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
                    <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <form method="GET" action="{{ route('admin.doc-categories.index') }}" class="p-6">
                            <!-- Preserve search parameter -->
                            @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Regulatory Body</label>
                                    <select name="regulatory_body"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All</option>
                                        <option value="SEBI" {{ request('regulatory_body') == 'SEBI' ? 'selected' : '' }}>SEBI</option>
                                        <option value="RBI" {{ request('regulatory_body') == 'RBI' ? 'selected' : '' }}>RBI</option>
                                        <option value="OTHER" {{ request('regulatory_body') == 'OTHER' ? 'selected' : '' }}>OTHER</option>
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
                                    <a href="{{ route('admin.doc-categories.index', request()->only('search')) }}"
                                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-center transition-colors duration-200 whitespace-nowrap">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Regulatory Body</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Sub-categories</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Documents</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-secondary-100 mr-3">
                                        <svg class="h-6 w-6 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        @if($category->short_description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($category->short_description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap">
                                @if($category->regulatory_body)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-secondary-50 text-secondary-500 border border-secondary-200">
                                    @if($category->regulatory_body == 'SEBI')
                                    <!-- SEBI Icon -->
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                    </svg>
                                    @elseif($category->regulatory_body == 'RBI')
                                    <!-- RBI Icon -->
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 12h4"></path>
                                        <path d="M10 8h4"></path>
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                        <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path>
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                                    </svg>
                                    @elseif($category->regulatory_body == 'OTHER')
                                    <!-- OTHER Icon -->
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2v20M2 12h20"></path>
                                    </svg>
                                    @endif
                                    {{ $category->regulatory_body }}
                                </span>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                @if($category->children->count() > 0)
                                <div class="text-sm text-gray-900">
                                    @foreach($category->children as $index => $subCategory)
                                    <span>{{ $subCategory->name }}</span>@if($index < $category->children->count() - 1), @endif
                                        @endforeach
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $category->children->count() }} {{ Str::plural('sub-category', $category->children->count()) }}</div>
                                @else
                                <span class="text-sm text-gray-400">No sub-categories</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <span class="text-sm text-gray-900">{{ $category->documents->count() + $category->subCategoryDocuments->count() }}</span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                {{ $category->created_at->format('d M Y') }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.edit'))
                                    <a href="{{ route('admin.doc-categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.delete'))
                                    <button type="button" onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No categories found.
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.create'))
                                <a href="{{ route('admin.doc-categories.create') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Create one</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="mt-6 flex items-center justify-between">
            {{ $categories->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 ml-3">Delete Document Category</h3>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Are you sure you want to delete this category? This will delete the category and all its sub-categories. This action cannot be undone.</p>
                    <p class="text-sm font-medium text-gray-900 mt-2" id="deleteCategoryName"></p>
                    <!-- Error Message Container -->
                    <div id="deleteErrorContainer" class="mt-4 hidden"></div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteBtn" onclick="confirmDeleteCategory()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteCategoryId = null;

        function openDeleteModal(id, name) {
            deleteCategoryId = id;
            document.getElementById('deleteCategoryName').textContent = name;
            document.getElementById('deleteErrorContainer').classList.add('hidden');
            document.getElementById('deleteErrorContainer').innerHTML = '';
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteCategoryId = null;
            document.getElementById('deleteErrorContainer').classList.add('hidden');
            document.getElementById('deleteErrorContainer').innerHTML = '';
        }

        function showDeleteError(message) {
            const errorContainer = document.getElementById('deleteErrorContainer');
            errorContainer.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${message}</p>
                        </div>
                    </div>
                </div>
            `;
            errorContainer.classList.remove('hidden');
        }

        function confirmDeleteCategory() {
            if (!deleteCategoryId) return;

            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalText = deleteBtn.textContent;
            deleteBtn.disabled = true;
            deleteBtn.textContent = 'Deleting...';

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ route("admin.doc-categories.destroy", ":id") }}'.replace(':id', deleteCategoryId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDeleteModal();
                        // Reload page to show updated list
                        window.location.reload();
                    } else {
                        showDeleteError(data.message || 'Failed to delete category.');
                        deleteBtn.disabled = false;
                        deleteBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showDeleteError('An error occurred while deleting the category.');
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = originalText;
                });
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Real-time Search with Debouncing
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const searchValue = e.target.value;

                // Debounce: wait 500ms after user stops typing
                searchTimeout = setTimeout(function() {
                    const url = new URL(window.location.href);
                    const currentParams = new URLSearchParams(window.location.search);

                    if (searchValue.trim() === '') {
                        currentParams.delete('search');
                    } else {
                        currentParams.set('search', searchValue);
                    }

                    // Preserve other filters
                    const regulatoryBody = currentParams.get('regulatory_body') || '';
                    const dateFrom = currentParams.get('date_from') || '';
                    const dateTo = currentParams.get('date_to') || '';

                    // Build new URL
                    url.search = currentParams.toString();

                    // Navigate to new URL
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
        document.addEventListener('click', function(event) {
            const filterToggle = document.getElementById('filterToggle');
            const filterDropdown = document.getElementById('filterDropdown');

            if (filterToggle && filterDropdown && !filterToggle.contains(event.target) && !filterDropdown.contains(event.target)) {
                filterDropdown.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>