@props([
    'title' => null,
    'subtitle' => null,
    'cards' => [],
    'columns' => 3, // 2, 3, or 4
    'bgColor' => 'bg-white',
])

@php
    // Calculate column width based on columns prop
    $columnClass = match($columns) {
        2 => 'lg:w-[calc(50%-1rem)]',
        3 => 'lg:w-[calc(33.333%-1.333rem)]',
        4 => 'lg:w-[calc(25%-1.5rem)]',
        default => 'lg:w-[calc(33.333%-1.333rem)]',
    };
@endphp

<div class="py-6 {{ $bgColor }} dark:bg-gray-900">
    <div class="cmsContainer">
        
        @if($title || $subtitle)
            <!-- Section Title -->
            <div class="text-center mb-[1.5rem] md:mb-[2.25rem] lg:mb-[3rem]">
                @if($title)
                    <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary">
                        {{ $title }}
                    </h2>
                @endif
                @if($subtitle)
                    <p class="mt-4 text-[1rem] lg:text-[1.25rem] font-medium">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        <!-- Cards Wrapper -->
        <div class="flex flex-wrap justify-center gap-6 lg:gap-8 lg:gap-y-12">
            @foreach($cards as $card)
                <div class="flex flex-col group relative bg-white rounded-2xl p-4 lg:p-[1.5rem] transform transition-all duration-300 ease-in-out hover:scale-[1.02] hover:shadow-2xl border border-quaternary-100 hover:border-primary-300
                    w-full sm:w-[calc(50%-0.75rem)]
                    {{ $columnClass }}
                ">
                    <div>
                        @if(isset($card['icon']))
                            <!-- Icon -->
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-500 rounded-[0.75rem] flex items-center justify-center mb-4 transition-all duration-300 group-hover:bg-primary-600 group-hover:scale-110">
                                <i class="{{ $card['icon'] }} text-xl sm:text-2xl text-white"></i>
                            </div>
                        @endif

                        @if(isset($card['title']))
                            <!-- Title -->
                            <h3 class="text-[1.125rem] md:text-[1.5rem] font-bold text-quaternary-900 transition-colors duration-300 group-hover:text-primary-600 font-sans">
                                {{ $card['title'] }}
                            </h3>
                        @endif

                        @if(isset($card['description']))
                            <!-- Description -->
                            <p class="mt-2 mb-4">
                                {{ $card['description'] }}
                            </p>
                        @endif
                    </div>

                    @if(isset($card['subPoints']) && count($card['subPoints']) > 0)
                        <!-- SubPoints -->
                        <div class="mt-2">
                            @if(isset($card['subPointsTitle']))
                                <h4 class="font-semibold mb-2 text-quaternary-900">{{ $card['subPointsTitle'] }}</h4>
                            @endif
                            <ul class="list-disc pl-5 space-y-1 mb-3 text-quaternary-700">
                                @foreach($card['subPoints'] as $point)
                                    <li>{!! $point !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(isset($card['process']))
                        <!-- Process -->
                        <div>
                            @if(isset($card['processTitle']))
                                <h4 class="font-semibold mb-2 text-quaternary-900">{{ $card['processTitle'] }}</h4>
                            @endif
                            <p class="text-quaternary-700 dark:text-gray-300 mb-3">
                                {{ $card['process'] }}
                            </p>
                        </div>
                    @endif

                    @if(isset($card['note']))
                        <!-- Note -->
                        <p class="italic font-bold text-quaternary-700 dark:text-gray-400 mb-3">
                            {{ $card['note'] }}
                        </p>
                    @endif

                    @if(isset($card['button']))
                        <!-- Button -->
                        <div class="mt-auto">
                            <a href="{{ $card['button']['url'] ?? '#' }}" 
                               class="flex flex-row items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-sm md:text-base font-medium shadow-lg transition-all duration-300 bg-primary-500 hover:bg-primary-600 hover:shadow-xl">
                                {{ $card['button']['text'] ?? 'Read More' }}
                                @if(isset($card['button']['icon']))
                                    <i class="{{ $card['button']['icon'] }}"></i>
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Optional Slot for Additional Content -->
        {{ $slot }}
    </div>
</div>
