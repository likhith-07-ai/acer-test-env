@props([
    'title' => 'Trusted Credit Ratings. Transparent Insights.',
    'description' => 'ACER – Empowering investors, issuers, and lenders with reliable credit ratings and in-depth research.',
    'bgImage' => 'assets/images/acer/banner_home_68b6ee84daf01.webp',
    'bgOverlay' => 'rgba(0,0,0,0.55)',
    'buttons' => [],
    'fullHeight' => true,
])

<section class="cmsContainer relative section-space !py-6">
    <div class="flex flex-col justify-end items-start rounded-3xl p-6 md:p-10 lg:px-12 lg:py-16 gap-4 text-left min-h-auto bg-black/55 {{ $fullHeight ? 'lg:min-h-[calc(100dvh-9rem)]' : '' }}" 
         style="background-image: linear-gradient(0deg, {{ $bgOverlay }}, {{ $bgOverlay }}), url('{{ asset($bgImage) }}'); background-size: cover; background-position: center;">
        
        <!-- Hidden image for SEO -->
        <img src="{{ asset($bgImage) }}" alt="" aria-hidden="true" class="w-0 h-0 absolute">

        @if($title)
            <!-- Heading -->
            <h1 class="font-caladea text-[2.5rem] md:text-[3.75rem] lg:text-[5rem] font-extrabold leading-[1.1] text-white mb-4 sm:mb-6">
                {{ $title }}
            </h1>
        @endif

        @if($description)
            <!-- Description -->
            <p class="font-satoshi text-base md:text-[1.25rem] font-medium text-white/90 leading-[1.2] mb-4 sm:mb-6">
                {{ $description }}
            </p>
        @endif

        @if(count($buttons) > 0)
            <!-- Buttons -->
            <div class="flex flex-wrap gap-4 mt-4 sm:mt-6">
                @foreach($buttons as $button)
                    <a href="{{ $button['url'] ?? '#' }}" 
                       class="flex flex-row items-center justify-center gap-2 px-6 py-3 text-base font-medium shadow-lg transition-all duration-300 rounded-xl
                              @if(($button['style'] ?? 'primary') === 'primary')
                                  bg-primary-500 text-white hover:brightness-110 hover:shadow-xl
                              @elseif(($button['style'] ?? 'primary') === 'secondary')
                                  bg-white text-quinary-700 hover:bg-gray-100 hover:shadow-lg
                              @elseif(($button['style'] ?? 'primary') === 'tertiary')
                                  text-quinary-700 hover:bg-gray-100 hover:shadow-lg
                              @endif"
                       @if(isset($button['bgColor']))
                           style="background-color: {{ $button['bgColor'] }};"
                       @endif>
                        {{ $button['text'] ?? 'Button' }}
                        @if(isset($button['icon']))
                            <i class="{{ $button['icon'] }}"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Optional Slot for Custom Content -->
        {{ $slot }}
    </div>
</section>
