<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">View Research Article</h1>
            <div class="flex gap-3">
                @if($researchArticle->status === 'approved' && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.publish')))
                <form method="POST" action="{{ route('admin.research-articles.publish', $researchArticle) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                        Publish
                    </button>
                </form>
                @endif
                @if((auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.edit')))
                <a href="{{ route('admin.research-articles.edit', $researchArticle) }}"
                    class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg">
                    Edit
                </a>
                @endif
                <a href="{{ route('admin.research-articles.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $researchArticle->title }}</h2>
                    @if($researchArticle->is_restricted)
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Restricted</span>
                    @endif
                </div>
                <div>
                    @php
                    $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-800',
                    'submitted' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'published' => 'bg-blue-100 text-blue-800',
                    ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded {{ $statusColors[$researchArticle->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($researchArticle->status) }}
                    </span>
                </div>
            </div>

            @if($researchArticle->featured_image)
            <div>
                <img src="{{ asset('storage/' . $researchArticle->featured_image) }}" alt="{{ $researchArticle->title }}" class="w-full h-auto rounded-lg">
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Category:</span>
                    <p class="font-medium">{{ $researchArticle->category->name ?? 'Uncategorized' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Author:</span>
                    <p class="font-medium">{{ $researchArticle->author->name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Created:</span>
                    <p class="font-medium">{{ $researchArticle->created_at->format('d M Y') }}</p>
                </div>
                @if($researchArticle->published_at)
                <div>
                    <span class="text-gray-500">Published:</span>
                    <p class="font-medium">{{ $researchArticle->published_at->format('d M Y') }}</p>
                </div>
                @endif
            </div>

            @if($researchArticle->tags->count() > 0)
            <div>
                <span class="text-gray-500">Tags:</span>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($researchArticle->tags as $tag)
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($researchArticle->excerpt)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Excerpt</h3>
                <p class="text-gray-700">{{ $researchArticle->excerpt }}</p>
            </div>
            @endif

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Content</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($researchArticle->content)) !!}
                </div>
            </div>

            @if($researchArticle->status === 'rejected' && $researchArticle->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="font-semibold text-red-900 mb-2">Rejection Reason</h4>
                <p class="text-red-700">{{ $researchArticle->rejection_reason }}</p>
            </div>
            @endif

            @if($researchArticle->status === 'submitted' && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('research-articles.approve')))
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Actions</h3>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('admin.research-articles.approve', $researchArticle) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.research-articles.reject', $researchArticle) }}" class="inline">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="rejection_reason" placeholder="Rejection reason..." required
                                class="px-4 py-2 border border-gray-300 rounded-lg">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>