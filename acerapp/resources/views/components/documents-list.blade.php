@props(['documents', 'categories', 'regulator' => 'SEBI', 'showTabs' => true])

<style>
    [x-cloak] {
        display: none !important;
    }

    .accordion-wrapper {
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }

    .accordion-content {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
</style>

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    if (!function_exists('formatFileSize')) {
        function formatFileSize($bytes)
        {
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
        function getFileSize($filePath)
        {
            if (!$filePath)
                return 'N/A';
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

<div class="py-6 bg-white dark:bg-gray-900 mb-8" x-data="documentsFilter" x-init="init()">
    <div class="cmsContainer">
        <!-- Filter and Search Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <!-- Heading with Icon -->
                <div class="flex-1" id="documents-heading">
                    <x-documents-heading :regulator="$regulator" />
                </div>

                <!-- Search Bar -->
                <div class="relative w-full md:w-[320px]">
                    <i class="acericon-search absolute left-3 top-1/2 -translate-y-1/2 text-quinary-400"></i>
                    <input type="text" placeholder="Search documents..." x-model="searchQuery"
                        @input.debounce.500ms="performSearch()" :disabled="isLoading"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-quaternary-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-primary-500 transition-all duration-300 font-medium text-sm md:text-base">
                    <div x-show="isLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs (if applicable) -->
            @if($showTabs)
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center gap-2">
                    <button @click="applyFilter('SEBI')" :disabled="isLoading"
                        :class="activeRegulator === 'SEBI' ? 'bg-primary-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 text-sm">
                        <i class="acericon-shield-check" x-show="!isLoading || activeRegulator !== 'SEBI'"></i>
                        <span>SEBI</span>
                    </button>
                    <button @click="applyFilter('RBI')" :disabled="isLoading"
                        :class="activeRegulator === 'RBI' ? 'bg-primary-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 text-sm">
                        <i class="acericon-bank" x-show="!isLoading || activeRegulator !== 'RBI'"></i>
                        <span>RBI</span>
                    </button>
                    <button @click="applyFilter('OTHER')" :disabled="isLoading"
                        :class="activeRegulator === 'OTHER' ? 'bg-primary-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 text-sm">
                        <i class="acericon-plus" x-show="!isLoading || activeRegulator !== 'OTHER'"></i>
                        <span>OTHER</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Documents Content -->
        <div id="documents-content-wrapper" x-data="accordionWrapper" x-init="initAccordions()">
            <x-documents-list-content :documents="$documents" :categories="$categories" :regulator="$regulator" />
        </div>
    </div>
</div>

<script>
    (function () {
        let componentsRegistered = false;

        function registerAlpineComponents() {
            if (componentsRegistered) return;
            if (typeof Alpine === 'undefined' || !Alpine.data) return;

            componentsRegistered = true;

            // Accordion wrapper component
            Alpine.data('accordionWrapper', () => ({
                get openAccordions() {
                    return this.$store.accordions || {};
                },
                toggleAccordion(key) {
                    if (!this.$store.accordions) {
                        this.$store.accordions = {};
                    }
                    this.$store.accordions[key] = !this.$store.accordions[key];
                },
                initAccordions() {
                    const accordions = this.$store.accordions || {};
                    @foreach($categories as $category)
                        if (!accordions.hasOwnProperty('accordion-{{ $category->id }}')) {
                            accordions['accordion-{{ $category->id }}'] = false;
                        }
                        @foreach($category->children as $subCategory)
                            if (!accordions.hasOwnProperty('sub-accordion-{{ $subCategory->id }}')) {
                                accordions['sub-accordion-{{ $subCategory->id }}'] = false;
                            }
                        @endforeach
                    @endforeach
                    this.$store.accordions = accordions;
                }
            }));

            // Documents filter component
            Alpine.data('documentsFilter', () => ({
                activeRegulator: '{{ $regulator }}',
                searchQuery: '{{ request('search', '') }}',
                isLoading: false,
                init() {
                    // Initialize accordions store
                    const accordions = this.$store.accordions || {};
                    @foreach($categories as $category)
                        if (!accordions.hasOwnProperty('accordion-{{ $category->id }}')) {
                            accordions['accordion-{{ $category->id }}'] = false;
                        }
                        @foreach($category->children as $subCategory)
                            if (!accordions.hasOwnProperty('sub-accordion-{{ $subCategory->id }}')) {
                                accordions['sub-accordion-{{ $subCategory->id }}'] = false;
                            }
                        @endforeach
                    @endforeach
                    this.$store.accordions = accordions;

                    // Ensure Alpine.js is initialized for the content wrapper after DOM is ready
                    this.$nextTick(() => {
                        const contentWrapper = document.getElementById('documents-content-wrapper');
                        if (contentWrapper && !contentWrapper.__x) {
                            Alpine.initTree(contentWrapper);
                        }
                    });
                },
                async applyFilter(regulator) {
                    if (this.isLoading) return;

                    this.activeRegulator = regulator;
                    this.isLoading = true;

                    const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

                    try {
                        const searchParam = this.searchQuery ? '&search=' + encodeURIComponent(this.searchQuery) : '';
                        const url = '{{ url()->current() }}?regulator=' + regulator + searchParam;

                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            document.getElementById('documents-heading').innerHTML = data.heading_html;
                            const contentContainer = document.getElementById('documents-content-wrapper');
                            contentContainer.innerHTML = data.documents_html;

                            // Reset accordions to closed on filter change
                            const accordions = Alpine.store('accordions') || {};
                            for (let key in accordions) {
                                if (key.startsWith('accordion-') || key.startsWith('sub-accordion-')) {
                                    accordions[key] = false;
                                }
                            }
                            Alpine.store('accordions', accordions);

                            window.scrollTo(0, scrollPosition);

                            // Reinitialize Alpine.js for new content
                            Alpine.initTree(contentContainer);

                            window.history.pushState({ regulator: regulator }, '', url);
                        }
                    } catch (error) {
                        console.error('Error loading documents:', error);
                        alert('Error loading documents. Please try again.');
                    } finally {
                        this.isLoading = false;
                    }
                },
                async performSearch() {
                    if (this.isLoading) return;

                    this.isLoading = true;
                    const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

                    try {
                        const searchParam = this.searchQuery ? '&search=' + encodeURIComponent(this.searchQuery) : '';
                        const url = '{{ url()->current() }}?regulator=' + this.activeRegulator + searchParam;

                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            document.getElementById('documents-heading').innerHTML = data.heading_html;
                            const contentContainer = document.getElementById('documents-content-wrapper');
                            contentContainer.innerHTML = data.documents_html;

                            // Open accordions if searching, otherwise can keep them as is or close
                            const accordions = Alpine.store('accordions') || {};
                            if (this.searchQuery.trim().length > 0) {
                                for (let key in accordions) {
                                    if (key.startsWith('accordion-') || key.startsWith('sub-accordion-')) {
                                        accordions[key] = true;
                                    }
                                }
                            } else {
                                // If search cleared, maybe close them?
                                for (let key in accordions) {
                                    if (key.startsWith('accordion-') || key.startsWith('sub-accordion-')) {
                                        accordions[key] = false;
                                    }
                                }
                            }
                            Alpine.store('accordions', accordions);

                            window.scrollTo(0, scrollPosition);

                            // Reinitialize Alpine.js for new content
                            Alpine.initTree(contentContainer);

                            window.history.pushState({ regulator: this.activeRegulator, search: this.searchQuery }, '', url);
                        }
                    } catch (error) {
                        console.error('Error searching documents:', error);
                        alert('Error searching documents. Please try again.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        }

        // Initialize Alpine store before registering components
        function initializeAlpineStore() {
            if (typeof Alpine !== 'undefined' && Alpine.store) {
                const accordions = Alpine.store('accordions') || {};
                @foreach($categories as $category)
                    if (!accordions.hasOwnProperty('accordion-{{ $category->id }}')) {
                        accordions['accordion-{{ $category->id }}'] = false;
                    }
                    @foreach($category->children as $subCategory)
                        if (!accordions.hasOwnProperty('sub-accordion-{{ $subCategory->id }}')) {
                            accordions['sub-accordion-{{ $subCategory->id }}'] = false;
                        }
                    @endforeach
                @endforeach
                Alpine.store('accordions', accordions);
            }
        }

        // Register components when Alpine is available
        if (typeof Alpine !== 'undefined' && Alpine.data) {
            initializeAlpineStore();
            registerAlpineComponents();
        }

        // Also listen for alpine:init event
        document.addEventListener('alpine:init', () => {
            initializeAlpineStore();
            registerAlpineComponents();
        });

        // Fallback: try after a short delay
        setTimeout(() => {
            initializeAlpineStore();
            registerAlpineComponents();
        }, 100);
    })();
</script>