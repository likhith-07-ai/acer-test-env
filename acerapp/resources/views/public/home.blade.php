@extends('layouts.public')

@section('title', 'ACER Credit Rating | SEBI Registered CRA | Corporate & Bank Loan Ratings')
@section('meta_description', 'ACER Credit Rating is a SEBI Registered Credit Rating Agency (CRA) providing corporate credit ratings, bank loan ratings, and independent financial risk assessments across India.')
@section('meta_keywords', 'ACER Credit Rating, SEBI Registered CRA, Corporate Ratings, Bank Loan Ratings, Credit Rating Agency India')

@section('content')
    <!-- Home Hero Banner -->
    <x-home-hero title="Trusted Credit Ratings. Transparent Insights."
        description="ACER – Empowering investors, issuers, and lenders with reliable credit ratings and in-depth research."
        bgImage="assets/images/acer/banner_home.webp" :buttons="[
            [
                'text' => 'View Latest Ratings',
                'url' => route('public.ratings.index'),
                'icon' => 'acericon-up-arrow',
                'style' => 'primary'
            ],
            [
                'text' => 'Explore Our Methodology',
                'url' => route('public.ratings.criteria'),
                'style' => 'tertiary',
                'bgColor' => '#D9FFF5'
            ],
            [
                'text' => 'Get Rated',
                'url' => route('public.contact'),
                'style' => 'secondary'
            ]
        ]" />
    <section class="py-6">
        <div class="cmsContainer">
            <div class="flex flex-wrap sm:gap-16">
                <!-- Left Block -->
                <div class="w-full flex flex-wrap lg:flex-nowrap sm:gap-[50] lg:gap-[160] lg:justify-between">
                    <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1]  mb-8 text-quaternary">Who We Are</h2>
                    <div class="lg:max-w-[37.5rem] xl:max-w-[53.75rem]">
                        <p class="sm:text-xl font-medium mb-4">ACER is India’s newest SEBI-registered credit rating agency,
                            committed to delivering independent, transparent, and reliable credit ratings. Our purpose is to
                            empower investors, lenders, and businesses with ratings they can trust-enabling informed
                            decisions and fostering confidence in India’s financial markets.</p>
                        <p class="sm:text-xl font-medium mb-4">With a clear vision to support India’s economic growth story,
                            ACER upholds the highest ethical and analytical standards, combining deep domain expertise,
                            advanced analytics, and rigorous processes to ensure every rating is objective, consistent, and
                            insightful.</p>
                        <p class="sm:text-xl font-medium mb-4">At ACER, we are not just rating credit, we are fueling
                            India’s journey towards a stronger and more resilient economy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-6">
        <div class="cmsContainer flex flex-col gap-8 md:gap-16">
            <article class="flex flex-col md:flex-row items-center gap-8 md:gap-20">
                <!-- Logo -->
                <div class="order-1 md:order-2 flex justify-center md:justify-end">
                    <div
                        class="w-[160px] sm:w-[200px] h-[160px] sm:h-[200px] bg-[#F9F9F9] rounded-2xl flex items-center justify-center overflow-hidden">
                        <img src="assets/images/acer/indian-overseas-bank.webp" alt="" aria-hidden="true"
                            class="max-w-[120px] sm:max-w-[160px] object-contain">
                    </div>
                </div>
                <!-- Content -->
                <div class="order-2 md:order-1 text-center md:text-left flex-1">
                    <h3 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1]  text-quaternary mb-4 md:mb-8">
                        Promoted by Indian Overseas Bank
                    </h3>
                    <p class="text-base sm:text-lg font-medium">ACER is proudly promoted by Indian Overseas Bank, one of
                        India’s leading public sector banks. With its long-standing reputation for integrity and service,
                        Indian Overseas Bank strengthens our foundation and supports our mission to deliver transparent and
                        reliable credit ratings.</p>
                </div>
            </article>
            <article class="flex flex-col md:flex-row items-center gap-8 md:gap-20">
                <!-- Logo -->
                <div class="order-1 md:order-1 flex justify-center md:justify-start">
                    <div
                        class="w-[160px] sm:w-[200px] h-[160px] sm:h-[200px] bg-[#F9F9F9] rounded-2xl flex items-center justify-center overflow-hidden">
                        <img src="assets/images/acer/central-bank-of-india.webp" alt="" aria-hidden="true"
                            class="max-w-[120px] sm:max-w-[160px] object-contain">
                    </div>
                </div>
                <!-- Content -->
                <div class="order-2 md:order-2 text-center md:text-left flex-1">
                    <h3 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1]  text-quaternary mb-4 md:mb-8">
                        Supported by Central Bank of India
                    </h3>
                    <p class="text-base sm:text-lg font-medium">ACER is supported by Central Bank of India, a trusted public
                        sector institution with a strong legacy in India’s financial system. Central Bank's investment
                        reinforces our commitment to financial stability, credibility, and service to India’s capital
                        markets.</p>
                </div>
            </article>
        </div>
    </section>
    <section class="py-6">
        <div class="cmsContainer">
            <h2 class="text-center text-[2.25rem] lg:text-[3.5rem] leading-[1.1]  mb-8 text-quaternary">
                About ACER Snapshot
            </h2>

            <div class="outer flex flex-wrap lg:flex-nowrap items-end gap-6 sm:gap-8 sm:mt-12 pb-6 ">
                <div
                    class="border border-quinary-100 px-4 py-8 rounded-3xl w-full sm:w-[calc(50%-2rem)]  h-full text-center transform transition-all duration-300 hover:-translate-y-2 ">
                    <div
                        class="w-[4.5rem] h-[4.5rem] bg-primary text-white rounded-2xl flex justify-center items-center mx-auto mb-8">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M35 18.6391V13.8006C35 11.0673 35 9.70065 34.3265 8.80898C33.653 7.91733 32.1302 7.48443 29.0845 6.61866C27.0037 6.02716 25.1693 5.31455 23.7038 4.66398C21.7057 3.777 20.7067 3.3335 20 3.3335C19.2933 3.3335 18.2943 3.777 16.2962 4.66398C14.8306 5.31455 12.9964 6.02716 10.9156 6.61866C7.86988 7.48443 6.34703 7.91733 5.67352 8.80898C5 9.70065 5 11.0673 5 13.8006V18.6391C5 28.0143 13.4379 33.6393 17.6567 35.8658C18.6685 36.3998 19.1743 36.6668 20 36.6668C20.8257 36.6668 21.3315 36.3998 22.3433 35.8658C26.562 33.6393 35 28.0143 35 18.6391Z"
                                stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
                            <path
                                d="M20 26.6668C20.2327 26.6668 20.449 26.5608 20.8815 26.3488L24.3587 24.6448C25.8974 23.8908 26.6667 23.5138 26.6667 22.9168V15.4169M20 26.6668C19.7674 26.6668 19.551 26.5608 19.1185 26.3488L15.6413 24.6448C14.1027 23.8908 13.3334 23.5138 13.3334 22.9168V15.4169M20 26.6668V19.1668M26.6667 15.4169C26.6667 14.8199 25.8974 14.4428 24.3587 13.6888L20.8815 11.9849C20.449 11.7729 20.2327 11.6669 20 11.6669C19.7674 11.6669 19.551 11.7729 19.1185 11.9849L15.6413 13.6888C14.1027 14.4428 13.3334 14.8199 13.3334 15.4169M26.6667 15.4169C26.6667 16.0139 25.8974 16.3909 24.3587 17.1448L20.8815 18.8488C20.449 19.0608 20.2327 19.1668 20 19.1668M13.3334 15.4169C13.3334 16.0139 14.1027 16.3909 15.6413 17.1448L19.1185 18.8488C19.551 19.0608 19.7674 19.1668 20 19.1668"
                                stroke="#ffffff" stroke-width="1.5" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h3
                        class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-quinary-950 font-sans">
                        Governance <br>Excellence</h3>
                    <p class="text-quinary">Strong board &amp; governance policies.</p>
                </div>
                <div
                    class="border border-quinary-100 px-4 py-8 rounded-3xl w-full sm:w-[calc(50%-2rem)]  h-full text-center transform transition-all duration-300 hover:-translate-y-2 lg:-bottom-6 relative">
                    <div
                        class="w-[4.5rem] h-[4.5rem] bg-primary text-white rounded-2xl flex justify-center items-center mx-auto mb-8">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M25 8.33337C25 11.0948 22.7615 13.3334 20 13.3334C17.2385 13.3334 15 11.0948 15 8.33337C15 5.57196 17.2385 3.33337 20 3.33337C22.7615 3.33337 25 5.57196 25 8.33337Z"
                                stroke="#ffffff" stroke-width="2"></path>
                            <path
                                d="M19.9999 13.3334V15M19.9999 15C19.9999 16.5532 19.9999 17.3297 20.2959 17.9424C20.6906 18.759 21.4478 19.408 22.4006 19.7464C23.1153 20 24.0213 20 25.8333 20C27.6453 20 28.5513 20 29.2659 20.2537C30.2188 20.592 30.9759 21.241 31.3706 22.0577C31.6666 22.6704 31.6666 23.4469 31.6666 25V26.6667M19.9999 15C19.9999 16.5532 19.9999 17.3297 19.7039 17.9424C19.3093 18.759 18.5521 19.408 17.5993 19.7464C16.8846 20 15.9786 20 14.1666 20C12.3546 20 11.4486 20 10.7339 20.2537C9.78104 20.592 9.02397 21.241 8.62927 22.0577C8.33325 22.6704 8.33325 23.4469 8.33325 25V26.6667"
                                stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path
                                d="M13.3333 31.6666C13.3333 34.4281 11.0947 36.6666 8.33325 36.6666C5.57184 36.6666 3.33325 34.4281 3.33325 31.6666C3.33325 28.9051 5.57184 26.6666 8.33325 26.6666C11.0947 26.6666 13.3333 28.9051 13.3333 31.6666Z"
                                stroke="#ffffff" stroke-width="2"></path>
                            <path
                                d="M36.6667 31.6666C36.6667 34.4281 34.4282 36.6666 31.6667 36.6666C28.9052 36.6666 26.6667 34.4281 26.6667 31.6666C26.6667 28.9051 28.9052 26.6666 31.6667 26.6666C34.4282 26.6666 36.6667 28.9051 36.6667 31.6666Z"
                                stroke="#ffffff" stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3
                        class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-quinary-950 font-sans">
                        Proven Methodology</h3>
                    <p class="text-quinary">Analytical rigor &amp; practical approach.</p>
                </div>
                <div
                    class="border border-quinary-100 px-4 py-8 rounded-3xl w-full sm:w-[calc(50%-2rem)]  h-full text-center transform transition-all duration-300 hover:-translate-y-2 ">
                    <div
                        class="w-[4.5rem] h-[4.5rem] bg-primary text-white rounded-2xl flex justify-center items-center mx-auto mb-8">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M3.33325 13.3333C3.33325 13.3333 10.7952 5 19.9999 5C29.2046 5 36.6666 13.3333 36.6666 13.3333"
                                stroke="#ffffff" stroke-width="2" stroke-linecap="round"></path>
                            <path
                                d="M35.9066 21.7416C36.4133 22.4521 36.6666 22.8075 36.6666 23.3333C36.6666 23.8591 36.4133 24.2145 35.9066 24.925C33.6298 28.1176 27.8153 35 19.9999 35C12.1846 35 6.37009 28.1176 4.09332 24.925C3.5866 24.2145 3.33325 23.8591 3.33325 23.3333C3.33325 22.8075 3.5866 22.4521 4.09332 21.7416C6.37009 18.549 12.1846 11.6666 19.9999 11.6666C27.8153 11.6666 33.6298 18.549 35.9066 21.7416Z"
                                stroke="#ffffff" stroke-width="2"></path>
                            <path
                                d="M25 23.3334C25 20.5719 22.7615 18.3334 20 18.3334C17.2385 18.3334 15 20.5719 15 23.3334C15 26.0949 17.2385 28.3334 20 28.3334C22.7615 28.3334 25 26.0949 25 23.3334Z"
                                stroke="#ffffff" stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3
                        class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-quinary-950 font-sans">
                        Commitment to Transparency</h3>
                    <p class="text-quinary">Emphasis on full disclosure.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Research Articles Section -->
    <section class="py-6">
        <div class="cmsContainer">
            <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] mb-8 text-quaternary text-center">Featured
                Research</h2>
            <p class="text-center mt-4 text-[1rem] lg:text-[1.25rem] font-medium"></p>

            <div class="outer flex flex-wrap lg:flex-nowrap gap-8 pt-4 lg:pt-16 xl:mt-12">
                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full  sm:w-[calc(50%-2rem)]  h-full  hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden h-[17.25rem]">
                        <img src="assets/images/acer/excellence.webp" alt="Governance Excellence"
                            class="w-full h-full aspect-square object-cover object-center">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        Governance Excellence</h3>
                    <p>Comprehensive analysis of Indian banking sector trends and credit risk assessment.</p>
                </div>
                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full  sm:w-[calc(50%-2rem)]  h-full lg:-top-16 relative hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden h-[17.25rem]">
                        <img src="assets/images/acer/esg.webp" alt="ESG Integration in Credit Ratings"
                            class="w-full h-full aspect-square object-cover object-center">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        ESG Integration in Credit Ratings</h3>
                    <p>Framework for incorporating environmental, social, and governance factors.</p>
                </div>
                <div
                    class="inner border border-[#E0E0E0] rounded-3xl p-6 w-full  sm:w-[calc(50%-2rem)]  h-full  hover:shadow-lg hover:border-primary-300 transform transition-all duration-300 hover:-translate-y-2">
                    <div class="rounded-2xl mb-8 overflow-hidden h-[17.25rem]">
                        <img src="assets/images/acer/infrastructure.webp" alt="Infrastructure Financing Trends"
                            class="w-full h-full aspect-square object-cover object-center">
                    </div>
                    <h3 class="mb-4 text-[1.375rem] md:text-[2rem] font-semibold leading-[1.125] text-[#202020] font-sans">
                        Infrastructure Financing Trends</h3>
                    <p>Market dynamics and risk factors in infrastructure project financing.</p>
                </div>
            </div>
            <!-- View All Button -->
            <div class="flex justify-center">
                <a href="{{ route('public.research.index') }}"
                    class="border border-quaternary-100 hover:shadow-lg  hover:border-primary-300 py-3 px-6 rounded-xl w-auto mt-6 inline-flex items-center justify-center gap-2 text-quaternary font-medium">
                    View All Research
                    <i class="acericon-up-arrow text-xs" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <x-contact-section sectionId="office-address-form" layout="split"
        title="Have specific research questions or need custom analysis?" subtitle="Our research team is here to help."
        :offices="[
            [
                'name' => 'ACER HQ',
                'address' => 'Unit-808, 8th Floor, Tower -B, Signature Tower, South City I, Sector 30, Gurugram, Haryana 122022',
                'phone' => '+91 124 460 7887',
                'email' => 'contact@acerratings.com '
            ],
            [
                'name' => 'Regional Office (Mumbai) ',
                'address' => '1513, C Wing, One BKC, Bandra Kurla Complex, Mumbai 400051',
                'phone' => '+91 22 1234 5678',
                'email' => 'contact@acerratings.com'
            ]
        ]" formTitle="Get in Touch"
        formSubtitle="Fill out the form below and our team will get back to you shortly."
        :formAction="route('public.contact.store')" :showContactButton="true" />
@endsection