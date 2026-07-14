@extends('layouts.public')

@section('title', 'ACER Research & Insights | Market Analysis & Sector Reports')
@section('meta_description', 'Access ACER Research & Insights including sector reports, market analysis, financial trends, and data-driven commentary for investors and stakeholders.')
@section('meta_keywords', 'ACER Research, Market Analysis India, Sector Reports, Financial Insights, Credit Market Trends')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero title="Research & Insights"
        subtitle="Our research division produces data-driven reports, market analyses, and sectoral studies to help investors, issuers, and policymakers make informed decisions."
        description="Every publication is based on rigorous research and advanced analytics." />

    <!-- Sub Banner -->
    <x-page-sub-banner :show="true" bgColor="#E1F0EC" title="Market Intelligence You Can Trust"
        subtitle="In-depth reports and expert insights for strategic decisions." :buttons="[
            ['text' => 'View Latest Reports', 'url' => route('public.regulator.sebi'), 'style' => 'primary', 'icon' => 'acericon-up-arrow'],
        ]" />

    {{-- Research Articles Component --}}
    {{-- <x-research-articles :articles="$articles" title="Latest Research Highlights" description=""
        titleClass="text-center" :showViewAll="false" /> --}}

    <section class="py-6">
        <div class="cmsContainer">
            <h2
                class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary font-regular mb-8 md:mb-12 text-center">
                Latest Research Highlights</h2>
            <p class="text-center mt-4 text-[1rem] lg:text-[1.25rem] font-medium"></p>

            <div class="outer flex flex-wrap lg:flex-nowrap gap-8 lg:pt-16 lg:mt-12">
                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full sm:w-[calc(50%-2rem)]  h-full  hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden">
                        <img src="assets/images/acer/outlook.webp" alt="Sector Outlook 2025: Steel"
                            class="w-full aspect-square object-cover">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        Sector
                        Outlook 2025: Steel</h3>
                    <p>Analyzes India’s steel sector, assessing industry dynamics, financial performance, and credit
                        outlook.</p>
                    <p class="mt-8 text-sm text-gray-500 italic">Full report coming soon.</p>
                </div>



                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full sm:w-[calc(50%-2rem)]  h-full lg:-top-16 relative hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden">
                        <img src="assets/images/acer/banking.webp" alt="Banking Sector NPA Trends: 2025"
                            class="w-full aspect-square object-cover">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        Banking
                        Sector NPA Trends: 2025</h3>
                    <p>Covers the movement of gross NPAs, capital adequacy changes, and sectoral lending trends.</p>
                    <p class="mt-8 text-sm text-gray-500 italic">Full report coming soon.</p>
                </div>
                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full sm:w-[calc(50%-2rem)]  h-full  hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden">
                        <img src="assets/images/acer/market.webp" alt="Corporate Bond Market Update"
                            class="w-full aspect-square object-cover">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        Corporate
                        Bond Market Update</h3>
                    <p>Insights on issuance volumes, yield spreads, and rating migration trends in India’s debt market.</p>
                    <p class="mt-8 text-sm text-gray-500 italic">Full report coming soon.</p>
                </div>
            </div>
            <!-- View All Button -->
        </div>
    </section>

    <x-card-grid title="Our Research Process" subtitle="At ACER, every research output undergoes:" :columns="4" :cards="[
            [
                'icon' => 'acericon-pole',
                'title' => 'Data Collection',
                'description' => 'Verified market and regulatory sources.'
            ],
            [
                'icon' => 'acericon-network',
                'title' => 'Analytical Framework',
                'description' => 'Quantitative and qualitative evaluation.'
            ],
            [
                'icon' => 'acericon-three-user',
                'title' => 'Peer Review',
                'description' => 'Internal cross-checking by senior analysts.'
            ],
            [
                'icon' => 'acericon-secure',
                'title' => 'Compliance Review',
                'description' => 'Ensuring SEBI-aligned publication.'
            ]
        ]" />

    <!-- Image CTA Section -->
    <x-image-cta title="Explore the latest rated instruments and issuers. "
        buttonText="View Latest Ratings" buttonUrl="{{ route('public.ratings.index') }}"
        backgroundImage="assets/images/acer/cta-bg.webp" />

    <!-- Disclaimer Section -->
    {{-- <x-disclaimer-banner label="Disclaimers" :descriptions="[
                    'All ACER research is intended for informational purposes and should not be construed as investment advice.',
                ]" :buttons="[
                    [
                        'text' => 'View Full Methodology',
                        'url' => '#',
                        'style' => 'primary',
                        'icon' => 'acericon-up-arrow',
                        'external' => true
                    ]
                ]" /> --}}

    <x-disclaimer-banner label="Disclaimers" :descriptions="[
            'ACER is engaged in the business of providing credit ratings and other permitted services and does not provide investment advice or recommendations, directly or indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or withdrawal, as and when warranted.',
            'Information used in assigning ratings has been obtained from sources believed to be reliable, including the rated entity; however, such information has not been independently audited or verified by ACER. While reasonable care has been exercised to ensure that the information contained herein is true and fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular purpose.',
            'All ratings and related analyses are statements of opinion, and ACER is not liable for any losses, direct or indirect, arising from use of this publication or its contents. Users are advised to exercise their own judgment and due diligence before making any decision based on the ratings.'
        ]" />

    <!-- Contact Section -->
    <x-contact-section layout="split" title="Have questions or need ratings?"
        subtitle="Our team is here to provide clarity and support." :offices="[
            [
                'name' => 'ACER HQ',
                'address' => 'Unit-808, 8th Floor, Tower -B, Signature Tower, South City I, Sector 30, Gurugram, Haryana 122022',
                'phone' => '+91 124 460 7887',
                'email' => 'contact@acerratings.com '
            ],
            [
                'name' => 'Regional Office (Mumbai) ',
                'address' => '1513, C Wing, One BKC, Bandra Kurla Complex, Mumbai 400051',
                'phone' => '91 22 1234 5678',
                'email' => 'contact@acerratings.com'
            ]
        ]"
        formTitle="Get in Touch" formSubtitle="Fill out the form below and our team will get back to you shortly."
        :formAction="route('public.contact.store')" :showContactButton="true" />
@endsection