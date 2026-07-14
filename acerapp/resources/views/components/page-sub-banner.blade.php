@props([
    'show' => false,
    'bgColor' => '#E1F0EC',
    'title' => null,
    'subtitle' => null,
    'buttons' => [],
])

@if($show)
    <!-- Main Hero Sub Banner for Inner Page -->
    <div class="relative section-space mb-6 !p-0 min-h-auto" style="background-color: {{ $bgColor }};">
        <div class="cmsContainer flex flex-col justify-end items-start rounded-0 !py-6 md:!py-10 lg:!py-20 gap-4 text-left">
            
            @if($title)
                <!-- Heading -->
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] font-bold mb-3 text-quaternary-950">
                    {{ $title }}
                </h2>
            @endif

            @if($subtitle)
                <!-- Description -->
                <p class="font-satoshi text-base md:text-[1.25rem] font-medium leading-[1.2]">
                    {{ $subtitle }}
                </p>
            @endif

            @if(count($buttons) > 0)
                <!-- Buttons -->
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach($buttons as $button)
                        <a href="{{ $button['url'] ?? '#' }}" 
                           class="flex flex-row items-center justify-center gap-2 px-6 py-3 text-base font-medium transition-all duration-300 rounded-xl
                                  @if(($button['style'] ?? 'primary') === 'primary')
                                      bg-primary-500 text-white hover:bg-primary-600 hover:shadow-xl
                                  @else
                                      bg-white text-quinary-700 hover:bg-gray-100 hover:shadow-xl
                                  @endif">
                            {{ $button['text'] ?? 'Button' }}
                            @if(isset($button['icon']))
                                <i class="{{ $button['icon'] }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Optional Slot for Custom Content -->
            {{ $slot }}
        </div>
    </div>
@endif
