<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Research Tags</h1>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.create'))
            <a href="{{ route('admin.research-tags.create') }}"
                class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-2.5 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Add Tag
            </a>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tag</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Articles</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tags as $tag)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-secondary-100 mr-3">
                                        <svg class="h-6 w-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
                                        @if($tag->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($tag->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <span class="text-sm text-gray-900">{{ $tag->articles->count() }}</span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                {{ $tag->created_at->format('d M Y') }}
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.edit'))
                                    <a href="{{ route('admin.research-tags.edit', $tag) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.delete'))
                                    <form method="POST" action="{{ route('admin.research-tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Are you sure? This will remove the tag from all articles.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No tags found.
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.create'))
                                <a href="{{ route('admin.research-tags.create') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Create one</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tags->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tags->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>