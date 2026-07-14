@extends('layouts.public')

@section('title', 'ACER Ratings Services | Corporate, Bank & Financial Institution Ratings')
@section('meta_description', 'Explore ACER Ratings services including corporate credit ratings, bank loan ratings, and financial institution risk assessments aligned with SEBI regulations.')
@section('meta_keywords', 'ACER Ratings Services, Corporate Credit Ratings, Bank Loan Ratings, Financial Institution Ratings India')

@section('content')
    <!-- Main Hero Banner -->
    <x-page-hero sectionClass="mb-6" title="Ratings"
        description="Published ratings with rationale, methodology, and disclosures, per SEBI regulations." />

    <x-card-grid title="Explore Ratings Information" :columns="3" :cards="[
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
        ]" />

    <!-- Disclaimer Section -->
    {{-- - <x-disclaimer-banner label="Disclaimers" :descriptions="[
                'Ratings are not investment recommendations.',
                'All disclosures are published in compliance with SEBI and are updated on a periodic basis.'
            ]" :buttons="[
                [
                    'text' => 'View Full Methodology',
                    'url' => '#',
                    'style' => 'primary',
                    'icon' => 'acericon-up-arrow',
                    'external' => true
                ],
                [
                    'text' => 'Visit SEBI Website',
                    'url' => 'https://www.sebi.gov.in/',
                    'style' => 'secondary',
                    'icon' => 'acericon-up-arrow',
                    'external' => true
                ]
            ]" />--}}
    <x-disclaimer-banner label="Disclaimers" :descriptions="[
            'ACER is engaged in the business of providing credit ratings and other permitted services and does not provide investment advice or recommendations, directly or indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or withdrawal, as and when warranted.',
            'Information used in assigning ratings has been obtained from sources believed to be reliable, including the rated entity; however, such information has not been independently audited or verified by ACER. While reasonable care has been exercised to ensure that the information contained herein is true and fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular purpose.',
            'All ratings and related analyses are statements of opinion, and ACER is not liable for any losses, direct or indirect, arising from use of this publication or its contents. Users are advised to exercise their own judgment and due diligence before making any decision based on the ratings.'
        ]" />
@endsection