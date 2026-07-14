<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $policy->title }}</h1>
            <div class="flex space-x-2">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.edit'))
                    <a href="{{ route('admin.policies.edit', $policy) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                @if($policy->file_path && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('policies.download')))
                    @php
                        $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);
                        $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
                        $safeTitle = preg_replace('/_+/', '_', $safeTitle);
                        $safeTitle = trim($safeTitle, '_');
                        $downloadFileName = $safeTitle . '_' . $policy->id . '.' . $extension;
                    @endphp
                    <a href="{{ route('admin.policies.download', $policy) }}" download="{{ $downloadFileName }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download PDF
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                @if($policy->icon)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Icon</dt>
                    <dd class="mt-1">
                        <img src="{{ asset('storage/' . $policy->icon) }}" alt="{{ $policy->title }}" class="h-20 w-20 rounded-lg object-cover">
                    </dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if($policy->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Access Type</dt>
                    <dd class="mt-1">
                        @if($policy->isRestricted())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Restricted
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Public
                            </span>
                        @endif
                    </dd>
                </div>
                @if($policy->tagline)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Tagline</dt>
                    <dd class="mt-1 text-sm text-gray-900 italic">{{ $policy->tagline }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $policy->creator->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $policy->created_at->format('M d, Y H:i') }}</dd>
                </div>
                @if($policy->updater)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $policy->updater->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $policy->updated_at->format('M d, Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        @if($policy->content)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Content</h2>
            <div class="prose max-w-none">
                {!! $policy->getRenderedContent() !!}
            </div>
        </div>
        @endif

        <!-- Audit Logs -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Audit Trail</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performed By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($policy->auditLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $log->action == 'create' ? 'bg-green-100 text-green-800' : ($log->action == 'update' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->performer->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->performed_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>

