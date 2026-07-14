@props(['document', 'fileSize' => 'N/A'])

@php
    use Illuminate\Support\Str;
    $fileExtension = $document->file_path ? strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) : 'PDF';
@endphp

<div class="flex items-center gap-4 p-4 border border-quaternary-100 rounded-lg hover:bg-slate-50/50 transition-colors">
    <!-- Document Icon -->
    <div
        class="flex-shrink-0 bg-secondary-100 text-secondary-500 w-10 h-10 flex justify-center items-center rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
    </div>

    <!-- Document Details -->
    <div class="flex-1 min-w-0">
        <h4 class="text-base font-semibold text-gray-900">{{ $document->title }}</h4>

        @if($document->description)
            <p class="text-sm text-gray-600 mb-1">{{ Str::limit($document->description, 150) }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
            <span>{{ $fileSize }} {{ $fileExtension }}</span>
            <!-- <span>{{ $document->updated_at->format('d M Y') }}</span> -->
        </div>
    </div>

    <!-- Status and Download -->
    <div class="flex items-center gap-3 flex-shrink-0">
        @if($document->isRestricted())
            <span
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
                Restricted
            </span>
        @else
            @if($document->file_path)
                <a href="{{ route('public.pdf.viewer', ['type' => 'document', 'id' => $document->id]) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <!-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg> -->
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                        <path
                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    Preview
                </a>
            @endif
        @endif
    </div>
</div>