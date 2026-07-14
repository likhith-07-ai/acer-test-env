<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $docCategory->name }}</h1>
            <div class="flex space-x-3">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.edit'))
                    <a href="{{ route('admin.doc-categories.edit', $docCategory) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('admin.doc-categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div>
                <h2 class="text-sm font-medium text-gray-500">Name</h2>
                <p class="mt-1 text-lg text-gray-900">{{ $docCategory->name }}</p>
            </div>

            @if($docCategory->short_description)
            <div>
                <h2 class="text-sm font-medium text-gray-500">Description</h2>
                <p class="mt-1 text-gray-900">{{ $docCategory->short_description }}</p>
            </div>
            @endif

            @if($docCategory->parent)
            <div>
                <h2 class="text-sm font-medium text-gray-500">Parent Category</h2>
                <p class="mt-1 text-gray-900">{{ $docCategory->parent->name }}</p>
            </div>
            @endif

            @if($docCategory->children->count() > 0)
            <div>
                <h2 class="text-sm font-medium text-gray-500 mb-3">Sub-categories ({{ $docCategory->children->count() }})</h2>
                <div class="space-y-2">
                    @foreach($docCategory->children as $child)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <span class="text-gray-900">{{ $child->name }}</span>
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('doc-categories.edit'))
                                <a href="{{ route('admin.doc-categories.edit', $child) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <h2 class="text-sm font-medium text-gray-500">Documents Count</h2>
                <p class="mt-1 text-gray-900">{{ $docCategory->documents->count() + $docCategory->subCategoryDocuments->count() }} documents</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-500">Created</h2>
                <p class="mt-1 text-gray-900">{{ $docCategory->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>

