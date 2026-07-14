<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Research Articles</h1>
            <div class="flex gap-3">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.approve'))
                <a href="{{ route('admin.research-articles.approval-queue') }}"
                    class="flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Approval Queue
                </a>
                @endif
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.create'))
                <a href="{{ route('admin.research-articles.create') }}"
                    class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5v14" />
                    </svg>
                    Create Article
                </a>
                @endif
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.research-articles.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="min-w-[150px]">
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <select name="is_restricted" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Access</option>
                        <option value="0" {{ request('is_restricted') === '0' ? 'selected' : '' }}>Public</option>
                        <option value="1" {{ request('is_restricted') === '1' ? 'selected' : '' }}>Restricted</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'category_id', 'is_restricted']))
                <a href="{{ route('admin.research-articles.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Articles Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($articles as $article)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $article->title }}</div>
                                    @if($article->is_restricted)
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Restricted</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $article->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $article->author->name }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'submitted' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'published' => 'bg-blue-100 text-blue-800',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColors[$article->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $article->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    @if((auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.publish')))
                                    <form method="POST" action="{{ route('admin.research-articles.publish', $article) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to publish this article?');">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Publish">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('admin.research-articles.preview', $article) }}"
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-900" title="Preview">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.research-articles.show', $article) }}"
                                        class="text-gray-600 hover:text-gray-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if((auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.edit')))
                                    <a href="{{ route('admin.research-articles.edit', $article) }}"
                                        class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if((auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.delete')))
                                    <form method="POST" action="{{ route('admin.research-articles.destroy', $article) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this article?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No articles found.
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.create'))
                                <a href="{{ route('admin.research-articles.create') }}" class="text-primary-600 hover:text-primary-900 font-medium">Create one</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $articles->links('vendor.pagination.tailwind') }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>