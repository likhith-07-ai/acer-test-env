<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Activity Log Details</h1>
                <p class="text-sm text-gray-500 mt-1">View detailed information about this activity</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}"
                class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Logs
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Action Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Action Details</h2>
                        @if($auditLog->action == 'create')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Created
                        </span>
                        @elseif($auditLog->action == 'update')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        @elseif($auditLog->action == 'delete')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Deleted
                        </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Model Type</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->model_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->description ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Record ID</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->auditable_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Changes -->
                @if($auditLog->old_data || $auditLog->new_data)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Changes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($auditLog->old_data)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Old Data</h3>
                            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($auditLog->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                        @endif
                        @if($auditLog->new_data)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">New Data</h3>
                            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ json_encode($auditLog->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Performed By</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->performer->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->performer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <p class="text-base text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $auditLog->performer->role ?? 'N/A')) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timestamp Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Timestamps</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Performed At</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->performed_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $auditLog->performed_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">IP Address</label>
                            <p class="text-base text-gray-900 mt-1">{{ $auditLog->ip_address ?? 'N/A' }}</p>
                        </div>
                        @if($auditLog->user_agent)
                        <div>
                            <label class="text-sm font-medium text-gray-500">User Agent</label>
                            <p class="text-xs text-gray-900 mt-1 break-words">{{ $auditLog->user_agent }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Related Model -->
                @if($auditLog->auditable_id)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Related Record</h2>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-500">Model: <span class="font-medium text-gray-900">{{ $auditLog->model_name }}</span></p>
                        <p class="text-sm text-gray-500">ID: <span class="font-medium text-gray-900">{{ $auditLog->auditable_id }}</span></p>
                        @php
                            // Map model names to their index routes
                            $routeMap = [
                                'Document' => 'admin.documents.index',
                                'DocCategory' => 'admin.doc-categories.index',
                                'Policy' => 'admin.policies.index',
                                'ResearchArticle' => 'admin.research-articles.index',
                                'ResearchCategory' => 'admin.research-categories.index',
                                'ResearchTag' => 'admin.research-tags.index',
                                'User' => 'admin.users.index',
                            ];
                            $routeName = $routeMap[$auditLog->model_name] ?? null;
                        @endphp
                        @if($routeName)
                        <a href="{{ route($routeName) }}" 
                           class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-900 text-sm font-medium transition-colors">
                            <span>View {{ $auditLog->model_name }} Listing</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

