@props([
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'bgImage' => 'assets/images/acer/banner-bg.webp',
    'bgOverlay' => 'rgba(0,0,0,0.5)',
    'sectionClass' => null,
])

<section class="{{ $sectionClass }}">
    <!-- Main Hero Banner for Inner Page -->
    <div class="relative section-space !p-0 min-h-auto bg-black/40" 
         style="background-image: linear-gradient(0deg, {{ $bgOverlay }}, {{ $bgOverlay }}), url('{{ asset($bgImage) }}'); background-size: cover; background-position: center;">
        <div class="cmsContainer flex flex-col justify-end items-start rounded-0 !py-6 md:!py-10 lg:!py-20 gap-4 text-left">
            
            @if($title)
                <!-- Heading -->
                <h1 class="font-caladea text-[2.5rem] md:text-[3.75rem] lg:text-[5rem] font-bold leading-[1.1] text-white mb-3">
                    {{ $title }}
                </h1>
            @endif

            @if($subtitle)
                <!-- Subtitle -->
                <h2 class="font-satoshi mb-4 text-[1.25rem] md:text-[2rem] font-medium leading-[1.25] text-white font-sans">
                    {{ $subtitle }}
                </h2>
            @endif

            @if($description)
                <!-- Description -->
                <p class="font-satoshi text-base md:text-[1.25rem] font-medium text-white/90 leading-[1.2]">
                    {{ $description }}
                </p>
            @endif

            <!-- Optional Slot for Custom Content -->
            {{ $slot }}
        </div>
    </div>
</section>
