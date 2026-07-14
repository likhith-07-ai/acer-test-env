@props(['documents', 'categories', 'regulator' => 'ALL'])

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    
    if (!function_exists('formatFileSize')) {
        function formatFileSize($bytes) {
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        }
    }
    
    if (!function_exists('getFileSize')) {
        function getFileSize($filePath) {
            if (!$filePath) return 'N/A';
            try {
                $fullPath = Storage::disk('public')->path($filePath);
                if (file_exists($fullPath)) {
                    return formatFileSize(filesize($fullPath));
                }
            } catch (\Exception $e) {
                return 'N/A';
            }
            return 'N/A';
        }
    }
@endphp

@if($categories->count() > 0)
    <div class="space-y-4" id="documents-content" 
         x-data="{ 
             get openAccordions() { 
                 return $store.accordions || {}; 
             },
             toggleAccordion(key) {
                 if (!$store.accordions) {
                     $store.accordions = {};
                 }
                 $store.accordions[key] = !$store.accordions[key];
             }
         }">
        @foreach($categories as $category)
            @php
                $categoryDocuments = $documents->where('category_id', $category->id)->whereNull('sub_category_id');
                $hasSubCategories = $category->children->count() > 0;
                $accordionId = 'accordion-' . $category->id;
            @endphp
            
            @if($categoryDocuments->count() > 0 || $hasSubCategories)
                <div class="bg-white rounded-lg shadow-sm border border-quaternary-100 overflow-hidden">
                    <!-- Category Header (Accordion) -->
                    <button
                        @click="toggleAccordion('{{ $accordionId }}')"
                        :aria-expanded="(openAccordions['{{ $accordionId }}'] || false).toString()"
                        aria-controls="{{ $accordionId }}-panel"
                        id="{{ $accordionId }}-trigger"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 transition-colors">
                        <div class="flex-1 text-left">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            @if($category->short_description)
                                <p class="text-sm text-gray-600 mt-1">{{ $category->short_description }}</p>
                            @endif
                        </div>
                        <svg
                            :class="openAccordions['{{ $accordionId }}'] ? 'rotate-180' : ''"
                            class="w-5 h-5 text-gray-500 transition-transform duration-300"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Category Content -->
                    <div
                        id="{{ $accordionId }}-panel"
                        role="region"
                        aria-labelledby="{{ $accordionId }}-trigger"
                        x-show="openAccordions['{{ $accordionId }}']"
                        x-transition:enter="transition ease-out duration-400"
                        x-transition:enter-start="opacity-0 -translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-4"
                        style="display: none;"
                        x-cloak
                        class="border-t border-gray-200">
                        <div class="p-6 space-y-4">
                            <!-- Main Category Documents -->
                            @if($categoryDocuments->count() > 0)
                                @foreach($categoryDocuments as $document)
                                    @php
                                        $docFileSize = getFileSize($document->file_path);
                                    @endphp
                                    @include('components.document-item', ['document' => $document, 'fileSize' => $docFileSize])
                                @endforeach
                            @endif

                            <!-- Sub-categories -->
                            @foreach($category->children as $subCategory)
                                @php
                                    $subCategoryDocuments = $documents->where('sub_category_id', $subCategory->id);
                                    $subAccordionId = 'sub-accordion-' . $subCategory->id;
                                @endphp
                                
                                @if($subCategoryDocuments->count() > 0)
                                    <div class="p-4 border border-quaternary-100 rounded-lg hover:bg-slate-50/50 transition-colors">
                                        <!-- Sub-category Header -->
                                        <button
                                            @click="toggleAccordion('{{ $subAccordionId }}')"
                                            :aria-expanded="(openAccordions['{{ $subAccordionId }}'] || false).toString()"
                                            aria-controls="{{ $subAccordionId }}-panel"
                                            id="{{ $subAccordionId }}-trigger"
                                            class="w-full flex items-center justify-between py-2 hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 transition-colors">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                                <span class="font-medium text-gray-900">{{ $subCategory->name }}</span>
                                            </div>
                                            <svg
                                                :class="openAccordions['{{ $subAccordionId }}'] ? 'rotate-180' : ''"
                                                class="w-4 h-4 text-gray-500 transition-transform duration-300"
                                                fill="none"
                                                stroke="currentColor"
                                                aria-hidden="true"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Sub-category Documents -->
                                        <div
                                            id="{{ $subAccordionId }}-panel"
                                            role="region"
                                            aria-labelledby="{{ $subAccordionId }}-trigger"
                                            x-show="openAccordions['{{ $subAccordionId }}']"
                                            x-transition:enter="transition ease-out duration-400"
                                            x-transition:enter-start="opacity-0 -translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-2"
                                            style="display: none;"
                                            x-cloak
                                            class="mt-2 ml-5 space-y-3">
                                            @foreach($subCategoryDocuments as $document)
                                                @php
                                                    $docFileSize = getFileSize($document->file_path);
                                                @endphp
                                                @include('components.document-item', ['document' => $document, 'fileSize' => $docFileSize])
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
        <p class="text-gray-600">No documents available.</p>
    </div>
@endif

