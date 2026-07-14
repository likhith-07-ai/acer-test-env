@props([
    'backgroundImage' => 'assets/images/acer/cta-bg_68b6f20e3ad4f.webp',
    'title',
    'description' => null,
    'buttonText' => null,
    'buttonUrl' => '#',
    'buttonIcon' => 'acericon-up-arrow',
    'minHeight' => 'lg:min-h-[35rem]',
    'target' => '_self',
])

<section class="py-6 bg-white dark:bg-gray-900">
    <div class="cmsContainer">
        <div class="relative flex rounded-3xl overflow-hidden shadow-xl {{ $minHeight }}">
            <!-- Background Image -->
            <img src="{{ asset($backgroundImage) }}" alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover">      
            
            <!-- Black Overlay (30%) -->
            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Content Box -->
            <div class="relative w-full flex flex-col items-center justify-center text-center px-6 py-12 sm:px-12 sm:py-20">
                <div class="bg-white/25 backdrop-blur-lg rounded-2xl p-6 lg:p-12">
                    <h4 class="text-[1.5rem] md:text-[2rem] lg:text-[3rem] leading-[1.1] font-bold text-white leading-tight mb-6 lg:mb-8">
                        {{ $title }}
                    </h4>
                    
                    @if($description)
                        <p class="text-base sm:text-lg md:text-xl text-white/90 mb-8">
                            {{ $description }}
                        </p>
                    @endif

                    @if($buttonText)
                        <a href="{{ $buttonUrl }}" 
                           target="{{ $target }}"
                           rel="{{ $target === '_blank' ? 'noopener noreferrer' : '' }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-sm md:text-base font-medium transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-lg">
                            {{ $buttonText }}
                            @if($buttonIcon)
                                <i class="{{ $buttonIcon }}" aria-hidden="true"></i>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
