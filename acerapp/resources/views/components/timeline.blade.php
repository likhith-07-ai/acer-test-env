@props([
    'title' => null,
    'steps' => [],
    'bgColor' => 'bg-white',
])

<section class="cmsContainer !py-6 px-4 {{ $bgColor }}" id="timelineOuter">
    @if($title)
        <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary-900 font-regular mb-8 md:mb-12 text-center">
            {{ $title }}
        </h2>
    @endif

    <!-- Timeline wrapper -->
    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12 lg:gap-[15.25rem]">

        <!-- Vertical Line -->
        <div id="timeline-line" class="absolute left-0 md:left-1/2 md:-translate-x-1/2 w-[6px] md:w-[12px] bg-quinary-100 overflow-hidden transition-colors duration-300 ease-in-out">
            <div id="timeline-fill" class="w-full h-0 bg-primary-500 transition-colors duration-300 ease-in-out"></div>
        </div>

        <!-- Steps -->
        <div class="relative w-full col-span-2 space-y-10 lg:space-y-16 ps-8 md:ps-0" id="parentWrapperforTimeline">
            @foreach($steps as $step)
                <div class="timeline-step relative grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-20 lg:gap-[15.25rem] items-center">

                    <!-- Dot -->
                    <span class="absolute -start-[2.4375rem] md:-start-[3.125rem] md:left-1/2 md:-translate-x-1/2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-8 md:h-8 bg-quinary-100 rounded-full flex items-center justify-center z-10">
                        <span class="-m-2.5 w-5 h-5 md:-m-4 md:w-8 md:h-8 bg-quinary-100 rounded-full transition-colors duration-300"></span>
                    </span>

                    @if(isset($step['imageFirst']) && $step['imageFirst'])
                        <!-- Content First (Right Side on Desktop, Order Last on Mobile) -->
                        <div class="order-last md:order-2">
                            @if(isset($step['title']))
                                <h3 class="text-[1.25rem] md:text-[2rem] leading-[1.25] font-bold mb-4 text-quaternary-900">{{ $step['title'] }}</h3>
                            @endif

                            @if(isset($step['description']))
                                <p class="text-[1rem] md:text-[1.25rem] leading-[1.4] mb-3 font-medium">{{ $step['description'] }}</p>
                            @endif

                            @if(isset($step['points']) && count($step['points']) > 0)
                                <ul class="space-y-2">
                                    @foreach($step['points'] as $point)
                                        <li class="flex items-start gap-2 text-[1rem] md:text-[1.25rem] leading-[1.4] font-medium">
                                            @if(isset($step['icon']))
                                                <i class="{{ $step['icon'] }} text-primary-500 mt-1 me-2"></i>
                                            @endif
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        @if(isset($step['image']))
                            <!-- Image (Left Side on Desktop, Order First on Mobile) -->
                            <div class="w-full aspect-[528/297] rounded-xl overflow-hidden order-first md:order-1">
                                <img src="{{ asset($step['image']) }}" alt="{{ $step['title'] ?? 'Timeline Image' }}" class="w-full aspect-[528/297] object-cover transform transition-transform duration-500 hover:scale-110">
                            </div>
                        @endif

                    @else
                        <!-- imageFirst is FALSE: Content Left, Image Right -->
                        
                        <!-- Content (Left Side on Desktop, Order 1) -->
                        <div class="order-last md:order-1 text-left md:pr-12">
                            @if(isset($step['title']))
                                <h3 class="text-[1.25rem] md:text-[2rem] leading-[1.25] font-bold mb-4 text-quaternary-900 font-sans">{{ $step['title'] }}</h3>
                            @endif

                            @if(isset($step['description']))
                                <p class="text-[1rem] md:text-[1.25rem] leading-[1.4] mb-3 font-medium">{{ $step['description'] }}</p>
                            @endif

                            @if(isset($step['points']) && count($step['points']) > 0)
                                <ul class="space-y-2 inline-block text-left">
                                    @foreach($step['points'] as $point)
                                        <li class="flex items-start gap-2 text-[1rem] md:text-[1.25rem] leading-[1.4] font-medium">
                                            @if(isset($step['icon']))
                                                <i class="{{ $step['icon'] }} text-primary-500 mt-1 me-2"></i>
                                            @endif
                                            <span>{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        @if(isset($step['image']))
                            <!-- Image (Right Side on Desktop, Order 2) -->
                            <div class="w-full aspect-[528/297] rounded-xl overflow-hidden order-first md:order-2">
                                <img src="{{ asset($step['image']) }}" alt="{{ $step['title'] ?? 'Timeline Image' }}" class="w-full aspect-[528/297] object-cover transform transition-transform duration-500 hover:scale-110">
                            </div>
                        @endif
                    @endif

                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    function setTimelineLine() {
        const steps = document.querySelectorAll(".timeline-step");
        const timelineLine = document.getElementById("timeline-line");
        const parent = document.getElementById("parentWrapperforTimeline");
        const fill = document.getElementById("timeline-fill");

        if (!steps.length || !timelineLine || !parent || !fill) return;

        const firstHalf = steps[0].offsetHeight / 2;
        const lastHalf = steps[steps.length - 1].offsetHeight / 2;
        const parentHeight = parent.offsetHeight;
        const lineHeight = Math.max(0, parentHeight - (firstHalf + lastHalf));

        timelineLine.style.top = firstHalf + "px";
        timelineLine.style.height = lineHeight + "px";

        // Reset fill on setup
        fill.style.height = "0px";
    }

    function updateTimelineFill() {
        const timelineLine = document.getElementById("timeline-line");
        const fill = document.getElementById("timeline-fill");
        const steps = document.querySelectorAll(".timeline-step");
        const dots = document.querySelectorAll(".timeline-step > span > span");
        const parentWrapper = document.getElementById("timelineOuter");

        if (!timelineLine || !fill || !steps.length || !parentWrapper) return;

        const heading = parentWrapper.querySelector("h2");
        if (!heading) return;

        const headingRect = heading.getBoundingClientRect();
        const headingVisible = headingRect.top <= window.innerHeight * 0.8; 

        if (!headingVisible) {
            fill.style.height = "0px";
            dots.forEach(d => {
                d.classList.remove("bg-primary-500");
                d.classList.add("bg-quinary-100");
            });
            return;
        }

        const lineRect = timelineLine.getBoundingClientRect();
        const lineTopDoc = lineRect.top + window.scrollY;
        const lineHeight = timelineLine.offsetHeight;
        const lineBottomDoc = lineTopDoc + lineHeight;

        const viewportCenterDoc = window.scrollY + window.innerHeight / 2;

        let progress = (viewportCenterDoc - lineTopDoc) / (lineBottomDoc - lineTopDoc);
        progress = Math.min(Math.max(progress, 0), 1);

        const reachedEnd = window.innerHeight + window.scrollY >= document.body.offsetHeight - 2;
        if (reachedEnd) progress = 1;

        const fillHeight = Math.round(progress * lineHeight);
        fill.style.height = fillHeight + "px";

        steps.forEach((step, i) => {
            const stepCenterDoc = step.getBoundingClientRect().top + window.scrollY + step.offsetHeight / 2;
            
            if (progress === 1 && i === steps.length - 1) {
                dots[i].classList.remove("bg-quinary-100");
                dots[i].classList.add("bg-primary-500");
            } else if (viewportCenterDoc >= stepCenterDoc) {
                dots[i].classList.remove("bg-quinary-100");
                dots[i].classList.add("bg-primary-500");
            } else {
                dots[i].classList.remove("bg-primary-500");
                dots[i].classList.add("bg-quinary-100");
            }
        });
    }

    window.addEventListener("load", setTimelineLine);
    window.addEventListener("resize", () => {
        setTimelineLine();
    });
    window.addEventListener("scroll", updateTimelineFill);
})();
</script>
@endpush
