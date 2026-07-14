@extends('layouts.public')

@section('title', 'ACER Rating Process | Transparent & Independent Evaluation')
@section('meta_description', 'Learn about ACER’s transparent and independent credit rating process including data analysis, management interaction, committee review, and final rating issuance.')
@section('meta_keywords', 'ACER Rating Process, Credit Evaluation Process, Independent Rating Committee, SEBI CRA Process')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero title="Our Rating Process" description="Transparent, structured, and compliant with SEBI regulations." />

    <!-- Sub Banner -->
    <x-page-sub-banner :show="true" bgColor="#E1F0EC" title="How We Assign Ratings"
        subtitle="ACER follows a rigorous, multi-stage evaluation to ensure ratings are unbiased, data-driven, and transparent." />

    <!-- Timeline Section -->
    <x-timeline title="Step-by-Step Process" :steps="[
            [
                'imageFirst' => false,
                'title' => 'Rating Request',
                'points' => [
                    'Rating request made to ACER.',
                    'Rating Agreement Signed and Fees Paid'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/our-ratings.webp'
            ],
            [
                'imageFirst' => true,
                'title' => 'Data Collection & Analysis',
                'points' => [
                    'Issuer submits the Data',
                    'Our analysts verify and benchmark this information against industry and macroeconomic data.'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Data-Collection-Analysis.webp'
            ],
            [
                'imageFirst' => false,
                'title' => 'Management Interaction',
                'points' => [
                    'Analyst team engages with the issuer\'s management for detailed discussions on business model, governance, and future outlook.'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Management-Interaction.webp'
            ],
            [
                'imageFirst' => true,
                'title' => 'Committee Review',
                'points' => [
                    'Findings are presented to an independent Rating Committee.',
                    'Committee evaluates risks, strengths, liquidity, and industry position before assigning a provisional rating.'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Internal-Committee-Review.webp'
            ],
            [
                'imageFirst' => false,
                'title' => 'Communication of Rating',
                'points' => [
                    'Rating decision is communicated to the issuer confidentially first.',
                    'Issuer has the opportunity to accept or appeal before public release.'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Communication-of-Rating.webp'
            ],
            [
                'imageFirst' => true,
                'title' => 'Publication',
                'points' => [
                    'Final rating is published on ACER\'s website and reported to SEBI as per regulatory norms.',
                    'Deliverables: Public Rating Report (PDF rationale, disclosures).'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Publication.webp'
            ],
            [
                'imageFirst' => false,
                'title' => 'Surveillance & Monitoring',
                'points' => [
                    'Ratings are continuously monitored.',
                    'Periodic reviews (typically every 6–12 months) and event-based reviews (in case of material changes) are conducted.'
                ],
                'icon' => 'acericon-double-tick',
                'image' => 'assets/images/acer/Surveillance-Monitoring.webp'
            ]
        ]" />

    <x-card-grid title="Appeal & Review Mechanism" :columns="3" :cards="[
            [
                'icon' => 'acericon-calendar',
                'title' => 'Appeal Window',
                'subPoints' => [
                    'Issuers may request a review if they disagree with the rating assigned.',
                    'Timeline : Within 5 working days of receiving the rating communication for initial ratings.'
                ]
            ],
            [
                'icon' => 'acericon-sad-user',
                'title' => 'Review Process',
                'subPoints' => [
                    'A separate Rating Committee (different members from the first committee) is constituted.',
                    'Fresh evidence, clarifications, and data can be presented by the issuer.',
                    'Final outcome is binding and published in line with SEBI regulations.'
                ]
            ],
            [
                'icon' => 'acericon-headphone',
                'title' => 'Contact for Appeals',
                'subPoints' => [
                    'Email: <a href=\'mailto:investor.grievance@acerratings.com\' class=\'hover:text-primary-600 hover:underline\'>investor.grievance@acerratings.com</a>'
                ]
            ]
        ]" />

    <!-- Disclaimer Section -->
    <x-disclaimer-banner label="Disclaimers" :descriptions="[
            'ACER is engaged in the business of providing credit ratings and other permitted services and does not provide investment advice or recommendations, directly or indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or withdrawal, as and when warranted.',
            'Information used in assigning ratings has been obtained from sources believed to be reliable, including the rated entity; however, such information has not been independently audited or verified by ACER. While reasonable care has been exercised to ensure that the information contained herein is true and fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular purpose.',
            'All ratings and related analyses are statements of opinion, and ACER is not liable for any losses, direct or indirect, arising from use of this publication or its contents. Users are advised to exercise their own judgment and due diligence before making any decision based on the ratings.'
        ]" />
@endsection