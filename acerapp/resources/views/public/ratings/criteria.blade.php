@extends('layouts.public')

@section('title', 'ACER Rating Criteria | Methodology & Risk Assessment Framework')
@section('meta_description', 'Understand ACER’s rating criteria, analytical methodology, and structured risk assessment framework used to evaluate creditworthiness and financial stability.')
@section('meta_keywords', 'ACER Rating Criteria, Credit Rating Methodology, Risk Assessment Framework, SEBI CRA Guidelines')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero title=" Ratings Criteria & Methodologies"
        subtitle="Our ratings criteria define the framework we use to assess creditworthiness across industries and instruments, ensuring consistency, transparency, and compliance with SEBI guidelines."
        description="Each rating issued by ACER is backed by a published methodology, outlining the factors, weightages, and benchmarks considered in the assessment." />
    <!-- Sub Banner -->
    <x-page-sub-banner :show="true" bgColor="#E1F0EC" title="A Transparent Approach to Credit Ratings"
        subtitle=" Clear criteria. Consistent evaluation. Reliable outcomes." :buttons="[
            ['text' => 'View Methodology on Regulator Page', 'url' => route('public.regulator.sebi'), 'style' => 'primary', 'icon' => 'acericon-up-arrow'],
        ]" />

    <!-- Main Content -->
    <div class="py-6 bg-white dark:bg-gray-900" bis_skin_checked="1">
        <div class="cmsContainer grid grid-cols-1 lg:grid-cols-[auto_26.25rem] gap-8 lg:gap-[5rem] items-center"
            bis_skin_checked="1">

            <!-- Title -->
            <div class="order-1 lg:order-2 text-center lg:text-left" bis_skin_checked="1">
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary">
                    What Are Ratings Criteria?
                </h2>
            </div>

            <!-- Content -->
            <div class="order-2 lg:order-1" bis_skin_checked="1">
                <div class="rounded-[1.5rem] lg:rounded-br-[0.25rem] p-6 md:p-12 text-white bg-primary"
                    bis_skin_checked="1">
                    <p class="md:text-[1.5rem] font-medium mb-6 last:mb-0">
                        Ratings criteria are structured, pre-defined frameworks that outline the key parameters used to
                        evaluate credit risk. They ensure every rating decision is objective, consistent, and aligned with
                        industry standards.
                    </p>
                    <p class="md:text-[1.5rem] font-medium mb-6 last:mb-0">
                        We update our criteria periodically to reflect market dynamics, regulatory changes, and evolving
                        best practices.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <x-card-grid title="Criteria Categories" :columns="5" :cards="[
            [
                'icon' => 'acericon-bulding',
                'title' => 'Corporate Issuers',
                'description' => 'Evaluates operational performance, industry risk, financial strength, and governance for manufacturing and service companies.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.index'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-bank',
                'title' => 'Banks & Financial Institutions',
                'description' => 'Assesses capital adequacy, asset quality, earnings, and risk management.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.criteria'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-infrastructure',
                'title' => 'Infrastructure & Project Finance',
                'description' => 'Focuses on cash flow predictability, contractual arrangements, and sponsor strength.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.process'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-conectivity',
                'title' => 'Structured Finance',
                'description' => 'Examines asset pool quality, credit enhancement, and transaction structure.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.criteria'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-globale',
                'title' => 'Sovereigns & Public Sector Entities',
                'description' => 'Considers fiscal metrics, political stability, and external balances.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.process'),
                    'icon' => 'acericon-up-arrow'
                ]
            ]
        ]" />

    <div class="py-6" bis_skin_checked="1">
        <div class="cmsContainer" bis_skin_checked="1">

            <!-- Section Title -->
            <div class="text-center mb-[1.5rem] md:mb-[2.25rem] lg:mb-[3rem]" bis_skin_checked="1">
                <h2 class="text-[2.25rem] lg:text-[3.5rem] leading-[1.1] text-quaternary mb-6 sm:mb-8 text-center">
                    Key Evaluation Parameters
                </h2>
            </div>

            <!-- Table Wrapper (Responsive Scroll) -->
            <div class="overflow-x-auto rounded-2xl border border-quaternary-100" bis_skin_checked="1">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded-2xl">
                    <thead class="dark:bg-gray-700 text-left">
                        <tr>
                            <th class="p-4 font-bold text-quaternary">Parameter</th>
                            <th class="p-4 font-bold text-quaternary">Definition</th>
                            <th class="p-4 font-bold text-quaternary">Example Metrics</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-quaternary-100 dark:border-gray-700 hover:bg-[#fcfcfc] ">
                            <td class="p-4 font-medium dark:text-gray-300">Leverage &amp; Coverage</td>
                            <td class="p-4 font-medium dark:text-gray-300">Measures debt burden and repayment ability</td>
                            <td class="p-4 font-medium dark:text-gray-300">Debt/Equity, DSCR, Interest Coverage</td>
                        </tr>
                        <tr class="border-t border-quaternary-100 dark:border-gray-700 hover:bg-[#fcfcfc] bg-[#fcfcfc]">
                            <td class="p-4 font-medium dark:text-gray-300">Profitability</td>
                            <td class="p-4 font-medium dark:text-gray-300">Earnings performance</td>
                            <td class="p-4 font-medium dark:text-gray-300">EBITDA Margin, ROCE</td>
                        </tr>
                        <tr class="border-t border-quaternary-100 dark:border-gray-700 hover:bg-[#fcfcfc] ">
                            <td class="p-4 font-medium dark:text-gray-300">Liquidity</td>
                            <td class="p-4 font-medium dark:text-gray-300">Cash flow adequacy</td>
                            <td class="p-4 font-medium dark:text-gray-300">Current Ratio, Operating Cash Flow</td>
                        </tr>
                        <tr class="border-t border-quaternary-100 dark:border-gray-700 hover:bg-[#fcfcfc] bg-[#fcfcfc]">
                            <td class="p-4 font-medium dark:text-gray-300">Industry Position</td>
                            <td class="p-4 font-medium dark:text-gray-300">Competitive strength</td>
                            <td class="p-4 font-medium dark:text-gray-300">Market Share, Pricing Power</td>
                        </tr>
                        <tr class="border-t border-quaternary-100 dark:border-gray-700 hover:bg-[#fcfcfc] ">
                            <td class="p-4 font-medium dark:text-gray-300">Governance &amp; Transparency</td>
                            <td class="p-4 font-medium dark:text-gray-300">Management integrity and disclosure quality</td>
                            <td class="p-4 font-medium dark:text-gray-300">Audit Quality, Board Structure</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Disclaimer Section -->
    {{-- <x-disclaimer-banner label="Disclaimers" :descriptions="[
                                            'n line with SEBI guidelines, all methodologies are publicly available and accessible. ACER ensures complete transparency by publishing both current and historical versions of our criteria.'
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

    <!-- FAQ Section -->
    <x-faq-section title="FAQ" :items="[
            [
                'question' => 'How often are criteria reviewed?',
                'answer' => 'At least once annually, or when significant market/regulatory changes occur.'
            ],
            [
                'question' => 'Do all sectors have unique criteria?',
                'answer' => 'Yes, criteria may be updated. Any changes are applied prospectively, not retroactively.'
            ],
            [
                'question' => 'Can criteria change after a rating is assigned?',
                'answer' => 'Yes, each sector follows specific criteria. These reflect industry risks, regulations, and practices.'
            ]
        ]" :initialVisible="1" />
@endsection