<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $researchCategory->name }}</h1>
            <div class="flex space-x-3">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-categories.edit'))
                    <a href="{{ route('admin.research-categories.edit', $researchCategory) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('admin.research-categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div>
                <h2 class="text-sm font-medium text-gray-500">Name</h2>
                <p class="mt-1 text-lg text-gray-900">{{ $researchCategory->name }}</p>
            </div>

            @if($researchCategory->description)
            <div>
                <h2 class="text-sm font-medium text-gray-500">Description</h2>
                <p class="mt-1 text-gray-900">{{ $researchCategory->description }}</p>
            </div>
            @endif

            @if($researchCategory->parent)
            <div>
                <h2 class="text-sm font-medium text-gray-500">Parent Category</h2>
                <p class="mt-1 text-gray-900">{{ $researchCategory->parent->name }}</p>
            </div>
            @endif

            <div>
                <h2 class="text-sm font-medium text-gray-500">Status</h2>
                <p class="mt-1">
                    @if($researchCategory->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </p>
            </div>

            @if($researchCategory->children->count() > 0)
            <div>
                <h2 class="text-sm font-medium text-gray-500 mb-3">Sub-categories ({{ $researchCategory->children->count() }})</h2>
                <div class="space-y-2">
                    @foreach($researchCategory->children as $child)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <span class="text-gray-900">{{ $child->name }}</span>
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-categories.edit'))
                                <a href="{{ route('admin.research-categories.edit', $child) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <h2 class="text-sm font-medium text-gray-500">Articles Count</h2>
                <p class="mt-1 text-gray-900">{{ $researchCategory->articles->count() }} articles</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-500">Created</h2>
                <p class="mt-1 text-gray-900">{{ $researchCategory->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>

