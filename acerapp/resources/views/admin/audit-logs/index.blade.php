<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Activity Logs</h1>
        </div>

        <!-- Filters and Controls Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <!-- Left Side: Action Filter Buttons -->
            <div class="flex items-center space-x-2 border border-gray-300 bg-white rounded-xl p-1">
                @php
                $allParams = request()->except('action');
                $createParams = array_merge(request()->all(), ['action' => 'create']);
                $updateParams = array_merge(request()->all(), ['action' => 'update']);
                $deleteParams = array_merge(request()->all(), ['action' => 'delete']);
                @endphp
                <a href="{{ route('admin.audit-logs.index', $allParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ !request('action') ? 'bg-primary-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    All
                </a>
                <a href="{{ route('admin.audit-logs.index', $createParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('action') == 'create' ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Created
                </a>
                <a href="{{ route('admin.audit-logs.index', $updateParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('action') == 'update' ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Updated
                </a>
                <a href="{{ route('admin.audit-logs.index', $deleteParams) }}"
                    class="flex items-center justify-center gap-1 min-w-[74px] px-3 py-1.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ request('action') == 'delete' ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Deleted
                </a>
            </div>

            <!-- Right Side: Search and Filter Icon -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="flex-1 md:flex-initial relative">
                    <div class="relative">
                        <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search logs..."
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
                        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="p-6">
                            @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('action'))
                            <input type="hidden" name="action" value="{{ request('action') }}">
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Model Type</label>
                                    <select name="model_type"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All Models</option>
                                        @foreach($modelTypes as $modelType)
                                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                            {{ $modelType }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($isSuperAdmin ?? false)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                                    <select name="user_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
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
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors text-center">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->action == 'create')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    Created
                                </span>
                                @elseif($log->action == 'update')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Updated
                                </span>
                                @elseif($log->action == 'delete')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Deleted
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($log->action) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $log->model_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($log->description ?? 'N/A', 60) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->performer->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->performer->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->performed_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->performed_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.audit-logs.show', $log) }}"
                                    class="text-primary-600 hover:text-primary-900">View Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No logs found</h3>
                                    <p class="mt-1 text-sm text-gray-500">No activity logs match your filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        function toggleFilterDropdown() {
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const filterToggle = document.getElementById('filterToggle');
            const filterDropdown = document.getElementById('filterDropdown');
            
            if (!filterToggle.contains(event.target) && !filterDropdown.contains(event.target)) {
                filterDropdown.classList.add('hidden');
            }
        });

        // Auto-submit search on Enter
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const search = this.value;
                const url = new URL(window.location.href);
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }
        });
    </script>
</x-admin-layout>

