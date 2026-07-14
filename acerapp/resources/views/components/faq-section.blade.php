@props([
    'title' => 'FAQ',
    'items' => [],
    'initialVisible' => 1,
    'sectionClass' => 'py-6 dark:bg-gray-950',
    'containerClass' => 'max-w-3xl mx-auto text-quaternary px-4 xl:px-0',
    'toggleStatus' => '1',
])

<section id="attribute" class="{{ $sectionClass }}" toggle-status="{{ $toggleStatus }}">
    <div class="{{ $containerClass }}">
        <!-- Header -->
        <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-center mb-12">
            {{ $title }}
        </h2>

        <!-- FAQ Items -->
        <div 
            id="faqContainer" 
            data-initial-visible="{{ $initialVisible }}"
            x-data="{
                openItems: {},
                init() {
                    // Initialize only first item as open by default
                    @foreach($items as $index => $item)
                        @if($index === 0)
                            this.openItems[{{ $index }}] = true;
                        @else
                            this.openItems[{{ $index }}] = false;
                        @endif
                    @endforeach
                },
                toggle(index) {
                    this.openItems[index] = !this.openItems[index];
                },
                isOpen(index) {
                    return this.openItems[index] || false;
                }
            }"
        >
            @foreach($items as $index => $item)
                <div 
                    class="faq-item border-b border-gray-200 dark:border-gray-800" 
                    data-index="{{ $index }}"
                >
                    <button
                        @click="toggle({{ $index }})"
                        :aria-expanded="isOpen({{ $index }}).toString()"
                        aria-controls="faq-panel-{{ $index }}"
                        id="faq-trigger-{{ $index }}"
                        class="faq-button w-full text-left py-5 flex justify-between items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 rounded"
                    >
                        <span class="text-2xl text-quaternary pr-8 font-bold">
                            {{ $item['question'] ?? '' }}
                        </span>
                        <i
                            class="acericon-down-angle text-xl transition-transform duration-200"
                            aria-hidden="true"
                            :style="isOpen({{ $index }}) ? 'transform: rotate(180deg);' : 'transform: rotate(0deg);'"
                        ></i>
                    </button>

                    <div
                        class="faq-content pb-5"
                        id="faq-panel-{{ $index }}"
                        role="region"
                        aria-labelledby="faq-trigger-{{ $index }}"
                        x-show="isOpen({{ $index }})"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-screen"
                        x-transition:leave-end="opacity-0 max-h-0"
                    >
                        <p class="pr-8">{{ $item['answer'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

