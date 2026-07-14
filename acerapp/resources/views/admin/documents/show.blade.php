<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $document->title }}</h1>
            <div class="flex space-x-2">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.edit'))
                    <a href="{{ route('admin.documents.edit', $document) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('documents.download'))
                    @php
                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                        $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $document->title);
                        $safeTitle = preg_replace('/_+/', '_', $safeTitle);
                        $safeTitle = trim($safeTitle, '_');
                        $downloadFileName = $safeTitle . '_' . $document->id . '.' . $extension;
                    @endphp
                    <a href="{{ route('admin.documents.download', $document) }}" download="{{ $downloadFileName }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Regulatory Body</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->regulator }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Access Type</dt>
                    <dd class="mt-1">
                        @if($document->isRestricted())
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
                <div>
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->category->name }}</dd>
                </div>
                @if($document->subCategory)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Sub Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->subCategory->name }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->creator->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('M d, Y H:i') }}</dd>
                </div>
                @if($document->updater)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->updater->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->updated_at->format('M d, Y H:i') }}</dd>
                </div>
                @endif
                @if($document->description)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $document->description }}</dd>
                </div>
                @endif
            </dl>
        </div>

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
                        @forelse($document->auditLogs as $log)
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

