@extends('layouts.public')

@section('title', 'ACER Media & Press | News, Announcements & Industry Updates')
@section('meta_description', 'Stay updated with ACER Media & Press releases, official announcements, regulatory updates, and industry developments related to credit ratings.')
@section('meta_keywords', 'ACER Press Release, Credit Rating News, Industry Announcements, SEBI Registered CRA Updates')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero sectionClass="" title="Media Press"
        description="Stay updated with ACER’s press announcements, rating actions, and media coverage." />

    <!-- Sub Banner -->
    <x-page-sub-banner :show="true" bgColor="#EEF2F5" title="Trusted Updates. Transparent Communication."
        subtitle="Access the latest press releases, regulatory announcements, and rating actions from ACER." />

    <div class="py-6 bg-white dark:bg-gray-900">
        <div class="cmsContainer">
            <!-- Section Title -->
            <div class="text-left mb-8 w-fit pb-1">
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary-900 font-regular text-center">
                    Company Overview
                </h2>
                <span class="text-[#E31E24] font-medium cursor-pointer hover:underline">Explore</span> to view
                SEBI Industry Classification Structure
            </div>

            <!-- Main Layout Layout -->
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <!-- Left Sidebar -->
                <div class="w-full lg:w-[350px] shrink-0 hidden lg:block h-full">
                    <h3 class="text-[1.25rem] md:text-[2rem] leading-[1.25] font-bold mb-4 text-quaternary-900">Our
                        recommendations</h3>

                    @if(!isset($searchQuery))
                        <!-- Filters can go here in the future -->
                        <div class="flex flex-col gap-4 mt-8 pb-32">
                            <div class="relative">
                                <select id="years" name="years"
                                    class="w-full p-2.5 text-sm text-gray-600 border border-gray-200 rounded bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none pr-10">
                                    <option>Select Year</option>
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>
                                <i
                                    class="acericon-down-angle absolute end-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400"></i>
                            </div>
                            <div class="relative">
                                <select id="months" name="months"
                                    class="w-full p-2.5 text-sm text-gray-600 border border-gray-200 rounded bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none pr-10">
                                    <option>Select Month</option>
                                    <option>All Months</option>
                                    <option>January</option>
                                    <option>February</option>
                                    <option>March</option>
                                </select>
                                <i
                                    class="acericon-down-angle absolute end-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Detail Area -->
                <div class="w-full lg:w-[calc(100%-382px)] flex flex-col gap-6">
                    @forelse($pressReleases as $pressRelease)
                        <div class="flex flex-col bg-white rounded-2xl p-4 border border-quaternary-100">
                            <!-- 1. Company Name Header & Social -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end">
                                <h4
                                    class="text-[1.125rem] md:text-[1.5rem] font-bold text-quaternary-900 transition-colors duration-300 group-hover:text-primary-600">
                                    {{ $pressRelease->company_name }}
                                </h4>

                                <!-- Social Icons -->
                                <div class="flex items-center gap-3 text-gray-400 hidden">
                                    <a href="#" class="hover:text-[#1877F2] transition-colors"><i
                                            class="ri-facebook-fill text-[20px]"></i></a>
                                    <a href="#" class="hover:text-black transition-colors"><i
                                            class="ri-twitter-x-line text-[18px]"></i></a>
                                    <a href="#" class="hover:text-[#0A66C2] transition-colors"><i
                                            class="ri-linkedin-fill text-[20px]"></i></a>
                                    <a href="#" class="hover:text-[#FBBF24] transition-colors"><i
                                            class="ri-mail-line text-[20px]"></i></a>
                                </div>
                            </div>

                            <!-- 2. Headline Summary with date -->
                            <p class="mt-2 mb-4">{{ $pressRelease->city }} | Rating Outstanding as on
                                {{ $pressRelease->date ? $pressRelease->date->format('F d, Y') : 'N/A' }}
                            </p>

                            <!-- 3. Listing (Instrument Category, Ratings) -->
                            @php
                                $instruments = (is_array($pressRelease->rating_action_table) && !empty($pressRelease->rating_action_table))
                                    ? collect($pressRelease->rating_action_table)
                                    : collect([]);

                                // Group by rating to simulate categories since there is no standard category
                                $groupedInstruments = $instruments->groupBy('current_rating');
                            @endphp

                            @if($groupedInstruments->isNotEmpty())
                                <div class="w-[calc(100%+2rem)] mb-2 border border-gray-200 border-l-0 border-r-0 rounded-sm -mx-4">
                                    <!-- Header -->
                                    <div
                                        class="grid grid-cols-12 gap-4 px-5 py-3.5 bg-white text-[14px] text-quaternary-900 font-semibold border-b border-gray-200">
                                        <div class="col-span-4">Instrument Category</div>
                                        <div class="col-span-5">Ratings</div>
                                        <div class="col-span-3 text-right"></div>
                                    </div>

                                    <div class="flex flex-col">
                                        @foreach($groupedInstruments as $rating => $group)
                                            @php
                                                $ratingLabel = $rating;

                                                // Guess Category 
                                                $category = 'Long Term';
                                                $firstInst = $group->first()['instrument_name'] ?? '';
                                                if (stripos($firstInst, 'short') !== false || stripos($firstInst, 'letter of credit') !== false) {
                                                    $category = 'Short Term';
                                                }
                                            @endphp
                                            <div class="border-t border-gray-200 last:border-b-0 first:border-t-0 bg-white"
                                                x-data="{ openCategory: false }">
                                                <div class="grid grid-cols-12 gap-4 px-5 py-4 items-center cursor-pointer transition-colors hover:bg-[#F9FAFB] group"
                                                    @click="openCategory = !openCategory">

                                                    <div class="col-span-4 text-gray-800 text-[14px]">{{ $category }}
                                                    </div>
                                                    <div class="col-span-5 font-semibold text-[#003B5C] text-[14px]">{{ $ratingLabel }}
                                                    </div>

                                                    <div class="col-span-3 flex justify-end">
                                                        <div
                                                            class="text-primary-600 text-[13px] font-semibold flex items-center gap-1 group-hover:text-[#003B5C]">
                                                            View Instrument
                                                            <i class="ri-arrow-down-s-line text-[16px] transition-transform duration-300"
                                                                :class="openCategory ? 'rotate-180' : ''"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dropdown content -->
                                                <div x-show="openCategory" x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;"
                                                    class="bg-[#F9FAFB] border-t border-gray-100 px-5 pt-4 pb-5">
                                                    <div class="grid grid-cols-1 md:grid-cols-3">
                                                        @foreach($group as $inst)
                                                            <div class="flex items-start">
                                                                <i class="ri-checkbox-blank-circle-fill text-[5px] text-gray-400 mt-2"></i>
                                                                <span class="text-[14px] text-gray-700 leading-snug">
                                                                    @if(isset($inst['amount_inr']))
                                                                        Rs. {{ $inst['amount_inr'] }} Crore {{ $inst['instrument_name'] }}
                                                                    @else
                                                                        {{ $inst['instrument_name'] }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Rating Rationale & Links -->
                            <div class="mt-4">
                                <h4
                                    class="text-[1.125rem] md:text-[1.5rem] font-bold text-quaternary-900 transition-colors duration-300 group-hover:text-primary-600">
                                    Rating rationale</h4>

                                <div class="flex flex-col gap-3">
                                    <p class="mt-2 mb-2">
                                        <a href="{{ $pressRelease->format === 'pdf' && $pressRelease->pdf_file ? route('public.pdf.viewer', ['type' => 'press-release', 'id' => $pressRelease->id]) : route('public.press-releases.show', $pressRelease->id) }}"
                                            onclick="window.open(this.href, 'RationalePopup', 'width=1000,height=800,resizable=yes,scrollbars=yes'); return false;"
                                            class="flex items-center gap-2 text-quaternary-900 hover:text-primary-600 hover:underline w-fit">

                                            @if($pressRelease->format === 'pdf' && $pressRelease->pdf_file)
                                                <span class="text-primary pdf-link">
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-download-icon lucide-download">
                                                        <path d="M12 15V3" />
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <path d="m7 10 5 5 5-5" />
                                                    </svg>
                                                </span>
                                            @endif

                                            <span>
                                                {{ $pressRelease->company_name }}: {{ $pressRelease->headline }}
                                            </span>

                                            @if(!($pressRelease->format === 'pdf' && $pressRelease->pdf_file))
                                                <span class="text-primary html-link">
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-arrow-up-right-icon lucide-arrow-up-right">
                                                        <path d="M7 7h10v10" />
                                                        <path d="M7 17 17 7" />
                                                    </svg>
                                                </span>
                                            @endif

                                        </a>
                                    </p>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500 bg-gray-50 border border-dashed border-gray-200 rounded-xl">
                            <i class="ri-file-search-line text-4xl mb-3 block text-gray-300"></i>
                            <p class="font-medium">No press releases available at the moment.</p>
                        </div>
                    @endforelse

                    @if($pressReleases->hasPages())
                        <div class="py-4 border-t border-gray-100">
                            {{ $pressReleases->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <x-disclaimer-banner label="Disclaimers" :descriptions="[
            'ACER is engaged in the business of providing credit ratings and other permitted services and does not provide investment advice or recommendations, directly or indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or withdrawal, as and when warranted.',
            'Information used in assigning ratings has been obtained from sources believed to be reliable, including the rated entity; however, such information has not been independently audited or verified by ACER. While reasonable care has been exercised to ensure that the information contained herein is true and fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular purpose.',
            'All ratings and related analyses are statements of opinion, and ACER is not liable for any losses, direct or indirect, arising from use of this publication or its contents. Users are advised to exercise their own judgment and due diligence before making any decision based on the ratings.'
        ]" />

@endsection