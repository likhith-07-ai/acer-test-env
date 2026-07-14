@extends('layouts.public')

@section('title', 'Card Grid Examples - ACER')

@section('content')
    <!-- Page Hero -->
    <x-page-hero 
        title="Card Grid Component Examples"
        subtitle="Reusable Card Grid Component Demonstrations"
        description="See how the card-grid component can be used in different configurations"
    />

    <!-- Example 1: 4 Column Cards (Simple) -->
    <x-card-grid 
        title="Our Core Values"
        :columns="4"
        :cards="[
            [
                'icon' => 'acericon-integrity',
                'title' => 'Integrity',
                'description' => 'Our Ratings reflect unbiased and independent judgement.'
            ],
            [
                'icon' => 'acericon-accuracy',
                'title' => 'Accuracy',
                'description' => 'Analytical rigour is the foundation of our credibility.'
            ],
            [
                'icon' => 'acericon-layer',
                'title' => 'Transparency',
                'description' => 'Clear methodologies and open disclosures are central to our ethos.'
            ],
            [
                'icon' => 'acericon-innovation',
                'title' => 'Innovation',
                'description' => 'We leverage AI and modern analytics for sharper insights.'
            ]
        ]"
    />

    <!-- Example 2: 3 Column Cards with Buttons -->
    <x-card-grid 
        title="Explore Ratings Information"
        :columns="3"
        bgColor="bg-gray-50"
        :cards="[
            [
                'icon' => 'acericon-book',
                'title' => 'Understanding Ratings',
                'description' => 'What do ratings mean? Learn how to interpret rating symbols and categories.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.index'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-layer',
                'title' => 'Ratings Criteria',
                'description' => 'Explore the frameworks and sector-specific benchmarks behind our ratings.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.criteria'),
                    'icon' => 'acericon-up-arrow'
                ]
            ],
            [
                'icon' => 'acericon-loop',
                'title' => 'Ratings Process',
                'description' => 'See the step-by-step rating journey: data collection, committee review, and publication.',
                'button' => [
                    'text' => 'Read More',
                    'url' => route('public.ratings.process'),
                    'icon' => 'acericon-up-arrow'
                ]
            ]
        ]"
    />

    <!-- Example 3: 3 Column Cards with SubPoints -->
    <x-card-grid 
        title="Appeal & Review Mechanism"
        :columns="3"
        :cards="[
            [
                'icon' => 'acericon-calendar',
                'title' => 'Appeal Window',
                'subPoints' => [
                    'Issuers may request a review if they disagree with the rating assigned.',
                    'Timeline: Within 30 days of receiving the rating communication.'
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
        ]"
    />

    <!-- Example 4: Complex Cards with All Features -->
    <x-card-grid 
        title="Policy Hub"
        :columns="3"
        bgColor="bg-gray-50"
        :cards="[
            [
                'icon' => 'acericon-judge',
                'title' => 'Code of Conduct',
                'description' => 'Our analysts and committees follow a strict Code of Conduct to ensure objectivity and independence in ratings.',
                'subPointsTitle' => 'Key Principles:',
                'subPoints' => [
                    'Integrity in all rating assignments.',
                    'Independence from commercial or issuer influence.',
                    'Confidentiality of client information.',
                    'No conflict of interest in rating decisions.'
                ],
                'button' => [
                    'text' => 'Download PDF',
                    'url' => '#',
                    'icon' => 'acericon-download'
                ]
            ],
            [
                'icon' => 'acericon-error',
                'title' => 'Conflict of Interest Policy',
                'description' => 'ACER has a clearly defined framework to avoid and manage conflicts of interest.',
                'subPointsTitle' => 'Policy Highlights:',
                'subPoints' => [
                    'Analysts cannot rate entities where they have financial interest.',
                    'Issuer-pays model disclosures are made public.',
                    'Separate teams for commercial (business development) and analytical functions.',
                    'Rating committee decisions are independent and free from commercial pressures.'
                ],
                'note' => 'ACER ensures complete separation of commercial considerations and analytical judgments.',
                'button' => [
                    'text' => 'Download PDF',
                    'url' => '#',
                    'icon' => 'acericon-download'
                ]
            ],
            [
                'icon' => 'acericon-download-file',
                'title' => 'Rating Withdrawal Policy',
                'description' => 'Ratings may be withdrawn under specific conditions, in line with SEBI regulations.',
                'subPointsTitle' => 'Conditions for Withdrawal:',
                'subPoints' => [
                    'Full redemption of the rated instrument.',
                    'Non-cooperation by issuer (after due notices).',
                    'Upon regulatory approval, if applicable.'
                ],
                'processTitle' => 'Process:',
                'process' => 'Issuer request → Due diligence by analysts → Rating Committee review → Public disclosure.',
                'note' => 'ACER ensures complete separation of commercial considerations and analytical judgments.',
                'button' => [
                    'text' => 'Download PDF',
                    'url' => '#',
                    'icon' => 'acericon-download'
                ]
            ]
        ]"
    />

@endsection
