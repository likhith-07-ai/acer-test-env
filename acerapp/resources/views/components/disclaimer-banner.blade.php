@props([
    'label' => 'Disclaimers',
    'descriptions' => [],
    'buttons' => []
])

<section class="py-6 bg-white dark:bg-gray-900">
    <div class="cmsContainer">
        {{-- <div class="bg-[#FAFAFA] border border-quaternary-100 rounded-2xl p-4 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-6 xl:gap-[10rem]"> --}}
        <div class="bg-[#FAFAFA] border border-quaternary-100 rounded-2xl p-4 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-8">
            
            <!-- Label -->
            <span class="px-4 py-1 rounded-lg border border-quaternary-100 text-[1.25rem] font-medium shrink-0">
                {{ $label }}
            </span>
            
            <!-- Disclaimer Text -->
            <div class="flex-1 text-[1.25rem] italic text-quaternary-700 leading-[1.75rem]">
                @foreach($descriptions as $desc)
                    <p class="text-sm mb-4 last:mb-0 font-medium">{{ $desc }}</p>
                @endforeach
            </div>

            <!-- Buttons -->
            @if(count($buttons) > 0)
                <div class="flex flex-col gap-2 shrink-0 w-full md:w-auto">
                    @foreach($buttons as $button)
                        <a href="{{ $button['url'] ?? '#' }}" 
                           target="{{ isset($button['external']) && $button['external'] ? '_blank' : '_self' }}"
                           rel="{{ isset($button['external']) && $button['external'] ? 'noopener noreferrer' : '' }}"
                           class="flex flex-row items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm md:text-base font-medium transition-all duration-300
                           @if(($button['style'] ?? 'primary') === 'primary')
                               bg-primary-500 text-white hover:brightness-110 hover:shadow-xl
                           @else
                               bg-white border border-quaternary-100 text-quaternary-700 hover:bg-gray-100
                           @endif">
                            {{ $button['text'] }}
                            @if(isset($button['icon']))
                                <i class="{{ $button['icon'] }} text-xs"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</section>
