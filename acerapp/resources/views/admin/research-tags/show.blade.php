<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $researchTag->name }}</h1>
            <div class="flex space-x-3">
                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-tags.edit'))
                    <a href="{{ route('admin.research-tags.edit', $researchTag) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('admin.research-tags.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div>
                <h2 class="text-sm font-medium text-gray-500">Name</h2>
                <p class="mt-1 text-lg text-gray-900">{{ $researchTag->name }}</p>
            </div>

            @if($researchTag->description)
            <div>
                <h2 class="text-sm font-medium text-gray-500">Description</h2>
                <p class="mt-1 text-gray-900">{{ $researchTag->description }}</p>
            </div>
            @endif

            <div>
                <h2 class="text-sm font-medium text-gray-500">Articles Count</h2>
                <p class="mt-1 text-gray-900">{{ $researchTag->articles->count() }} articles</p>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-500">Created</h2>
                <p class="mt-1 text-gray-900">{{ $researchTag->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>

