@props(['title' => 'Board of Directors', 'directors' => []])

<section class="py-6">
    <div class="cmsContainer">
        <!-- Section Heading -->
        @if($title)
        <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] mb-8 text-quaternary text-center">
            {{ $title }}
        </h2>
        @endif

        <!-- Flex Layout -->
        <div class="flex flex-wrap justify-center -mx-2 xl:max-w-[62%] 2xl:max-w-[75%] xl:px-4 mx-auto">
            @foreach($directors as $director)
                <div class="px-2 w-full sm:w-1/2 lg:w-1/3 mb-4 flex">
                    <div class="rounded-3xl w-full text-center transition-all duration-300 hover:-translate-y-2">
                        <!-- Image Card -->
                        <div
                            class="group bg-gradient-to-r from-secondary-100 via-secondary-50 to-secondary-100 aspect-[416/520] rounded-3xl overflow-hidden relative">
                            <img src="{{ asset($director['image']) }}" alt="{{ $director['name'] }}"
                                class="w-full h-full object-cover">

                            <!-- Overlay Wrapper -->
                            <div class="absolute bottom-4 left-4 w-[calc(100%-2rem)]">
                                <div class="flex flex-col">
                                    <!-- Top Box (always visible) -->
                                    <div class="bg-white/25 backdrop-blur-[3rem] rounded-2xl p-3">
                                        <h3
                                            class="mb-1 text-[1.33rem] xl:text-[1rem] 2xl:text-[1.2rem] font-semibold leading-[1.125] text-white font-sans">
                                            {{ $director['name'] }}
                                        </h3>
                                        <p class="text:[1rem] xl:text-[0.875rem] text-white font-medium">
                                            {{ $director['position'] }}</p>
                                    </div>

                                    <!-- Bottom Box (slides in on hover) -->
                                    <div
                                        class="bg-white/25 backdrop-blur-[3rem] rounded-2xl group-hover:p-2 group-hover:mt-2 
                                                transform translate-y-full opacity-0 transition-all duration-500 ease-in-out
                                                group-hover:translate-y-0 group-hover:opacity-100 text-white font-medium leading-[0] group-hover:leading-[1.5] text:[1rem] xl:text-[0.875rem]">
                                        {{ $director['description'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>