@extends('layouts.public')

@section('title', 'RBI Disclosures | ACER Ratings')
@section('meta_description', 'Review ACER’s RBI disclosures and regulatory compliance practices.')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero title="RBI" description="Our commitment to integrity, transparency, and compliance with RBI guidelines." />

    <!-- Sub Banner -->
    <x-page-sub-banner :show="true" bgColor="#E1F0EC" title="RBI Disclosures"
        subtitle="At ACER, we operate with the highest standards of ethics and regulatory compliance. Below you can access our official RBI disclosures." />

    <!-- Governance Committees Section -->
    <div x-data="{ activeTab: 'nrc' }" class="py-12 bg-white">
        <div class="cmsContainer">
            <div class="flex flex-col items-center justify-center">
                <div
                    class="inline-flex flex-wrap justify-center p-1.5 bg-gray-100 rounded-2xl border border-gray-200 gap-1">
                    <button @click="activeTab = 'nrc'"
                        :class="activeTab === 'nrc' ? 'bg-primary-900 text-white shadow-lg border-primary-900' : 'text-quaternary-500 hover:text-quaternary-900 border-transparent'"
                        class="px-6 py-3 rounded-[12px] font-bold transition-all duration-300 text-base md:text-lg border">
                        NRC
                    </button>
                    <button @click="activeTab = 'rsc'"
                        :class="activeTab === 'rsc' ? 'bg-primary-900 text-white shadow-lg border-primary-900' : 'text-quaternary-500 hover:text-quaternary-900 border-transparent'"
                        class="px-6 py-3 rounded-[12px] font-bold transition-all duration-300 text-base md:text-lg border">
                        RSC
                    </button>
                    <button @click="activeTab = 'arc'"
                        :class="activeTab === 'arc' ? 'bg-primary-900 text-white shadow-lg border-primary-900' : 'text-quaternary-500 hover:text-quaternary-900 border-transparent'"
                        class="px-10 py-3 rounded-[12px] font-bold transition-all duration-300 text-base md:text-lg border">
                        ARC
                    </button>
                    <button @click="activeTab = 'cro'"
                        :class="activeTab === 'cro' ? 'bg-primary-900 text-white shadow-lg border-primary-900' : 'text-quaternary-500 hover:text-quaternary-900 border-transparent'"
                        class="px-6 py-3 rounded-[12px] font-bold transition-all duration-300 text-base md:text-lg border">
                        CRO
                    </button>
                </div>
            </div>
        </div>

        <!-- Nomination and Remuneration Committee (NRC) -->
        <div x-show="activeTab === 'nrc'" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <x-board-of-directors title="Nomination and Remuneration Committee (NRC)" :directors="[
            [
                'name' => 'Dr. M.S. Sahoo',
                'position' => 'Independent Director',
                'description' => 'Former Chairman of IBBI, former SEBI Whole-time Member',
                'image' => 'assets/images/acer/Dr_MS_Sahoo.webp'
            ],
            [
                'name' => 'Sunil Mehta',
                'position' => 'Chairman',
                'description' => 'Former CEO of IBA, former MD & CEO of PNB',
                'image' => 'assets/images/acer/Sunil_Mehta.webp'
            ],
            [
                'name' => 'Ajay Kumar Choudhary',
                'position' => 'Independent Director',
                'description' => 'Chairman NPCI, former Executive Director of RBI',
                'image' => 'assets/images/acer/Ajay_Kumar_Choudhary.webp'
            ]
        ]" />
        </div>

        <!-- Ratings Sub-committee of the Board (RSC) -->
        <div x-show="activeTab === 'rsc'" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <x-board-of-directors title="Ratings Sub-committee of the Board (RSC)" :directors="[
            [
                'name' => 'Ajay Kumar Choudhary',
                'position' => 'Independent Director',
                'description' => 'Chairman NPCI, former Executive Director of RBI',
                'image' => 'assets/images/acer/Ajay_Kumar_Choudhary.webp'
            ],
            [
                'name' => 'Dr. M.S. Sahoo',
                'position' => 'Independent Director',
                'description' => 'Former Chairman of IBBI, former SEBI Whole-time Member',
                'image' => 'assets/images/acer/Dr_MS_Sahoo.webp'
            ],
            [
                'name' => 'Dr. Girraj Prasad Gupta',
                'position' => 'Independent Director',
                'description' => 'Former CGA of Ministry of Finance, Former Chairman of the J&K Committee',
                'image' => 'assets/images/acer/Dr_Girraj_Prasad_Gupta.webp'
            ]
        ]" />
        </div>

        <!-- Audit & Risk Committee (ARC) -->
        <div x-show="activeTab === 'arc'" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <x-board-of-directors title="Audit & Risk Committee (ARC)" :directors="[
            [
                'name' => 'Dr. Girraj Prasad Gupta',
                'position' => 'Independent Director',
                'description' => 'Former CGA of Ministry of Finance, Former Chairman of the J&K Committee',
                'image' => 'assets/images/acer/Dr_Girraj_Prasad_Gupta.webp'
            ],
            [
                'name' => 'Ajay Kumar Choudhary',
                'position' => 'Independent Director',
                'description' => 'Chairman NPCI, former Executive Director of RBI',
                'image' => 'assets/images/acer/Ajay_Kumar_Choudhary.webp'
            ],
            [
                'name' => 'Kamlesh Kumar Taneja',
                'position' => 'Executive Director',
                'description' => 'Former General Manager at Central Bank of India',
                'image' => 'assets/images/acer/Kamlesh_Kumar_Taneja.webp'
            ]
        ]" />
        </div>

        <!-- Chief Ratings Officer (CRO) -->
        <div x-show="activeTab === 'cro'" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <x-board-of-directors title="Chief Ratings Officer (CRO)" :directors="[
            [
                'name' => 'Dr. Sarnambar Roy',
                'position' => 'Chief Ratings Officer (CRO)',
                'description' => '',
                'image' => 'assets/images/acer/Sarnambar_Roy.webp'
            ]
        ]" />
        </div>
    </div>
    <!-- Documents Component -->
    <x-documents-list :documents="$documents" :categories="$categories" :regulator="$regulator" :showTabs="false" />

    <!-- Contact Section -->
    <x-contact-section layout="split" title="Contact for Compliance Queries" :offices="[
            [
                'name' => 'Compliance Queries',
                'icon' => 'acericon-user',
                'email' => 'sebi.investorgrievance@acerratings.com'
            ]
        ]" formTitle="Get in Touch"
        formSubtitle="Fill out the form below and our team will get back to you shortly."
        :formAction="route('public.contact.store')" :showContactButton="true" :showLabels="false" />

    <!-- Disclaimer Section -->
    <x-disclaimer-banner label="Disclaimers" :descriptions="[
            'ACER is engaged in the business of providing credit ratings and other permitted services and does not provide investment advice or recommendations, directly or indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or withdrawal, as and when warranted.',
            'Information used in assigning ratings has been obtained from sources believed to be reliable, including the rated entity; however, such information has not been independently audited or verified by ACER. While reasonable care has been exercised to ensure that the information contained herein is true and fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular purpose.',
            'All ratings and related analyses are statements of opinion, and ACER is not liable for any losses, direct or indirect, arising from use of this publication or its contents. Users are advised to exercise their own judgment and due diligence before making any decision based on the ratings.'
        ]" />
@endsection