@php
    $regulator = $regulator ?? 'ALL';
@endphp

<div class="flex flex-col">
    <div class="flex items-center mb-1">
        @if($regulator === 'SEBI')
            <h2 class="text-[1.5rem] md:text-[2.5rem] leading-[1.1] font-regular text-quaternary-900">
                SEBI Disclosures
            </h2>
        @elseif($regulator === 'RBI')
            <h2 class="text-[1.5rem] md:text-[2.5rem] leading-[1.1] font-regular text-quaternary-900">
                RBI Disclosures
            </h2>
        @elseif($regulator === 'OTHER')
            <h2 class="text-[1.5rem] md:text-[2.5rem] leading-[1.1] font-regular text-quaternary-900">
                OTHER Disclosures
            </h2>
        @else
            <h2 class="text-[1.5rem] md:text-[2.5rem] leading-[1.1] font-regular text-quaternary-900">
                All Disclosures
            </h2>
        @endif
    </div>
    @if($regulator === 'SEBI')
        <p class="text-quinary-500 font-medium text-sm md:text-base">Securities and Exchange Board of India regulatory
            documents</p>
    @elseif($regulator === 'RBI')
        <p class="text-quinary-500 font-medium text-sm md:text-base">Reserve Bank of India regulatory documents</p>
    @elseif($regulator === 'OTHER')
        <p class="text-quinary-500 font-medium text-sm md:text-base">Financial sector regulatory documents</p>
    @endif
</div>