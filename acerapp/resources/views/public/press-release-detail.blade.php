@extends('layouts.minimal')

@push('styles')
    <style>
        /* Default rich content styling */
        .prose {
            color: #374151;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .prose p:empty {
            display: none;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            color: #111827;
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            line-height: 1.3;
            font-family: 'Satoshi', sans-serif;
        }

        .prose h1 {
            font-size: 2.25em;
        }

        .prose h2 {
            font-size: 1.5em;
        }

        .prose h3 {
            font-size: 1.25em;
        }

        .prose h4 {
            font-size: 1em;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.5em;
            margin-bottom: 1rem;
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.5em;
            margin-bottom: 1rem;
        }

        .prose li {
            margin-bottom: 0.25em;
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            border: 1px solid #000;
        }

        .prose th,
        .prose td {
            border: 1px solid #000;
            padding: 0.25rem 0.5rem;
            text-align: left;
        }

        .prose th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .prose tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
@endpush

@section('title', $pressRelease->company_name . ' | ACER Press Release')
@section('meta_description', Str::limit(strip_tags($pressRelease->brief_summary), 150))

@section('content')
    <div class="min-h-screen font-sans">
        <div class="mx-auto bg-white overflow-hidden p-8 md:p-12">

            <!-- HEADER SECTION -->
            <div class="border-b-2 border-primary-500 pb-6 mb-8 relative">
                <!-- Mobile Logo (Right aligned above text) -->
                <div class="flex justify-end mb-4 md:hidden">
                    <img src="{{ asset('assets/images/acer/logo.svg') }}" alt="ACER" class="h-8 w-auto">
                </div>
                <!-- Desktop Logo (Absolute top right) -->
                <div class="absolute top-0 right-0 hidden md:block">
                    <img src="{{ asset('assets/images/acer/logo.svg') }}" alt="ACER" class="h-12 w-auto">
                </div>

                <div class="text-left font-sans md:pr-32">
                    <h1 class="text-3xl font-bold text-black mb-2">{{ $pressRelease->company_name }}</h1>
                    <p class="text-black font-medium">
                        {{ $pressRelease->city ? $pressRelease->city . ', ' : '' }}{{ $pressRelease->date ? $pressRelease->date->format('F d, Y') : '' }}
                    </p>
                    <h2 class="text-xl font-semibold text-black mt-6">{{ $pressRelease->headline }}</h2>

                    @if($pressRelease->pdf_file)
                        <div class="mt-6">
                            <a href="{{ route('public.pdf.viewer', ['type' => 'press-release', 'id' => $pressRelease->id]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors shadow-sm">
                                <i class="ri-file-pdf-fill text-lg"></i>
                                View PDF
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RATING ACTION TABLE -->
            @if(!empty($pressRelease->rating_action_table) && is_array($pressRelease->rating_action_table))
                <div class="mb-2">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-black">
                            <thead class="bg-gray-100 text-black text-sm">
                                <tr>
                                    <th class="p-3 border border-black text-center whitespace-nowrap">Instrument/ Facility**
                                    </th>
                                    <th class="p-3 border border-black text-center">Amount<br>(INR Crore)</th>
                                    <th class="p-3 border border-black text-center">Current<br>Ratings</th>
                                    <th class="p-3 border border-black text-center">Rating Action</th>
                                    <th class="p-3 border border-black text-center">Regulator^</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($pressRelease->rating_action_table as $action)
                                    <tr>
                                        <td class="p-3 border border-black">
                                            {{ $action['instrument_name'] ?? ($action['instrument'] ?? '') }}
                                        </td>
                                        <td class="p-3 border border-black text-center">
                                            {{ $action['amount_inr'] ?? ($action['amount'] ?? '') }}
                                        </td>
                                        <td class="p-3 border border-black font-semibold text-center">
                                            {{ $action['current_rating'] ?? ($action['rating'] ?? '') }}
                                        </td>
                                        <td class="p-3 border border-black text-center">
                                            {{ $action['rating_action'] ?? ($action['action'] ?? '') }}
                                        </td>
                                        <td class="p-3 border border-black text-center">{{ $action['regulator'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="mb-4">
                <h6 class="text-lg font-bold text-black">Details of Instruments*</h6>
                <p>*Details of Facilities/ Instruments are in Annexure 2</p>
                <p>**Facility-wise lender details are at Annexure 3</p>
                <p>^List of facilities corresponding to respective financial sector regulator (FSR) are in Annexure 5</p>
            </div>
            <div class="mb-2">
                <p><strong class="text-black">Unsupported rating:</strong> {{ $pressRelease['unsupported_rating'] ?? ''  }}
                </p>
            </div>

            <!-- ANALYTICAL APPROACH & BRIEF SUMMARY -->
            <div class="mb-2 space-y-6">
                @if($pressRelease->analytical_approach)
                    <div>
                        <h3 class="text-xl font-bold text-black mb-2">Analytical Approach</h3>
                        <div class="text-black prose max-w-none">{!! $pressRelease->analytical_approach !!}</div>
                    </div>
                @endif
                @if($pressRelease->brief_summary)
                    <div>
                        <h3 class="text-xl font-bold text-black mb-2 pb-2 border-b">Brief Summary</h3>
                        <div class="text-black prose max-w-none">{!! $pressRelease->brief_summary !!}</div>
                    </div>
                @endif
            </div>

            <!-- STRENGTHS -->
            @if(!empty($pressRelease->strengths) && is_array($pressRelease->strengths))
                <div class="mb-2">
                    <h3 class="text-xl font-bold text-black mb-2 pb-2 border-b">Key Rating Drivers - Strengths</h3>
                    <ul class="space-y-4 list-disc pl-5">
                        @foreach($pressRelease->strengths as $strength)
                            @if(!empty($strength['title']))
                                <li class="pl-2">
                                    <span class="font-bold text-black">{{ $strength['title'] }}:</span>
                                    <div class="text-black prose max-w-none mt-1">{!! $strength['body'] ?? '' !!}</div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- WEAKNESSES -->
            @if(!empty($pressRelease->weaknesses) && is_array($pressRelease->weaknesses))
                <div class="mb-2">
                    <h3 class="text-xl font-bold text-black mb-2 pb-2 border-b">Key Rating Drivers - Weaknesses</h3>
                    <ul class="space-y-4 list-disc pl-5">
                        @foreach($pressRelease->weaknesses as $weakness)
                            @if(!empty($weakness['title']))
                                <li class="pl-2">
                                    <span class="font-bold text-black">{{ $weakness['title'] }}:</span>
                                    <div class="text-black prose max-w-none mt-1">{!! $weakness['body'] ?? '' !!}</div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- LIQUIDITY & OUTLOOK -->
            <div class="mb-2">
                @if($pressRelease->liquidity || $pressRelease->liquidity_body)
                    <h3 class="text-lg font-bold text-black mb-2">Liquidity Indicator: {{ $pressRelease->liquidity }}
                    </h3>
                    <div class="text-black prose max-w-none">{!! $pressRelease->liquidity_body !!}</div>
                @endif
            </div>

            <!-- SENSITIVITIES -->
            <div class="mb-10">
                <h3 class="text-xl font-bold text-black mb-2 pb-2 border-b">Rating Sensitivities</h3>
                <div class="grid grid-cols-1 gap-6">
                    <!-- Positive -->
                    @if(!empty($pressRelease->positive_sensitivities) && is_array($pressRelease->positive_sensitivities))
                        <div>
                            <h4 class="font-bold text-black mb-3 flex items-center gap-2">Positive Sensitivities</h4>
                            <ul class="list-disc pl-5 text-sm text-black space-y-2">
                                @foreach($pressRelease->positive_sensitivities as $pos)
                                    @if(!empty($pos['text']))
                                    <li>{{ $pos['text'] }}</li> @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Negative -->
                    @if(!empty($pressRelease->negative_sensitivities) && is_array($pressRelease->negative_sensitivities))
                        <div>
                            <h4 class="font-bold text-black mb-3 flex items-center gap-2">Negative Sensitivities</h4>
                            <ul class="list-disc pl-5 text-sm text-black space-y-2">
                                @foreach($pressRelease->negative_sensitivities as $neg)
                                    @if(!empty($neg['text']))
                                    <li>{{ $neg['text'] }}</li> @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ABOUT COMPANY -->
            @if($pressRelease->about_company_body)
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-black mb-2 pb-2 border-b">About the Company</h3>
                    <div class="text-black prose max-w-none mb-4">{!! $pressRelease->about_company_body !!}</div>

                    @if(!empty($pressRelease->company_segments_table) && is_array($pressRelease->company_segments_table) && count($pressRelease->company_segments_table) > 0 && !empty($pressRelease->company_segments_table[0]['group']))
                        <table class="w-full text-left border-collapse border border-black text-sm mt-4">
                            <thead class="bg-gray-100 text-black">
                                <tr>
                                    <th class="p-3 border border-black">Macro Economic Indicator</th>
                                    <th class="p-3 border border-black">Sector</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressRelease->company_segments_table as $seg)
                                    <tr>
                                        <td class="p-3 border border-black">{{ $seg['group'] ?? '' }}</td>
                                        <td class="p-3 border border-black">{{ $seg['description'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif

            <!-- FINANCIALS -->
            @if(!empty($pressRelease->fy_columns) && is_array($pressRelease->fy_columns) && count($pressRelease->fy_columns) > 0 && !empty($pressRelease->fy_columns[0]['label']))
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-black mb-2">Financial Indicators
                        ({{ $pressRelease->financials_basis ?? 'Standalone' }})</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-black text-sm">
                            <thead class="bg-gray-100 text-black font-bold">
                                <tr>
                                    <th class="p-3 border border-black text-left">Particulars</th>
                                    @foreach($pressRelease->fy_columns as $col)
                                        <th class="p-3 border border-black text-right">{{ $col['label'] ?? '' }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">Operating Revenue (Rs. Crore)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['revenue'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">EBITDA (Rs. Crore)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['ebitda'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">EBITDA Margin (%)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['ebitda_margin'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">Interest Coverage (x)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['coverage'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">Net Leverage (x)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['leverage'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                                <tr>
                                    <td class="p-3 border border-black font-semibold">PAT Margin (%)</td>
                                    @foreach($pressRelease->fy_columns as $col) <td class="p-3 border border-black text-right">
                                        {{ $col['pat_margin'] ?? '' }}
                                    </td> @endforeach
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-sm italic">Source: {{ $pressRelease->financials_source }}</p>
                    </div>
                </div>
            @endif

            <!-- ANNEXURES -->
            <h3 class="text-2xl font-black text-black mb-6 mt-12 uppercase border-b-2 border-black pb-2">Annexures
            </h3>

            <!-- ANNEXURE 1: Rating History -->
            @if(!empty($pressRelease->annexure_1_rating_history) && is_array($pressRelease->annexure_1_rating_history) && count($pressRelease->annexure_1_rating_history) > 0 && !empty($pressRelease->annexure_1_rating_history[0]['instrument']))
                <div class="mb-4">
                    <h4 class="font-bold text-black text-lg">Annexure - 1: Rating History for the Last Three Years</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse border border-black">
                            <thead class="bg-gray-100 text-black">
                                <tr>
                                    <th class="p-2 border border-black" rowspan="2">Instrument type</th>
                                    <th class="p-2 border border-black" rowspan="2">Rating type</th>
                                    <th class="p-2 border border-black" rowspan="2">Rated<br>limits (INR<br>crore)</th>
                                    <th class="p-2 border border-black" rowspan="2">Current<br>ratings</th>
                                    <th class="p-2 border border-black text-center" colspan="3">Historical rating/ Outlook/
                                        Watch</th>
                                </tr>
                                <tr>
                                    <th class="p-2 border border-black pb-4 text-center">
                                        @if(!empty($pressRelease->annexure_1_rating_history[0]['year1_date']))
                                            <span>{{ \Carbon\Carbon::parse($pressRelease->annexure_1_rating_history[0]['year1_date'])->format('d-M-y') }}</span>
                                        @endif
                                    </th>
                                    <th class="p-2 border border-black pb-4 text-center">
                                        @if(!empty($pressRelease->annexure_1_rating_history[0]['year2_date']))
                                            <span>{{ \Carbon\Carbon::parse($pressRelease->annexure_1_rating_history[0]['year2_date'])->format('d-M-y') }}</span>
                                        @endif
                                    </th>
                                    <th class="p-2 border border-black pb-4 text-center">
                                        @if(!empty($pressRelease->annexure_1_rating_history[0]['year3_date']))
                                            <span>{{ \Carbon\Carbon::parse($pressRelease->annexure_1_rating_history[0]['year3_date'])->format('d-M-y') }}</span>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressRelease->annexure_1_rating_history as $row)
                                    <tr>
                                        <td class="p-2 border border-black">{{ $row['instrument'] ?? '' }}</td>
                                        <td class="p-2 border border-black">{{ $row['type'] ?? '' }}</td>
                                        <td class="p-2 border border-black text-right">{{ $row['limits'] ?? '' }}</td>
                                        <td class="p-2 border border-black font-bold">{{ $row['current_rating'] ?? '' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['year1_rating'] ?? '' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['year2_rating'] ?? '' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['year3_rating'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- ANNEXURE 1.1: Complexity Level -->
            @if(!empty($pressRelease->annexure_1_1_complexity) && is_array($pressRelease->annexure_1_1_complexity) && count($pressRelease->annexure_1_1_complexity) > 0 && !empty($pressRelease->annexure_1_1_complexity[0]['instrument']))
                <div class="mb-4">
                    <h4 class="font-bold text-black text-lg">Annexure - 1.1: Complexity Level of Rated Instruments</h4>
                    <table class="w-full text-sm text-left border-collapse border border-black">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border border-black">Instrument</th>
                                <th class="p-2 border border-black">Complexity Indicator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pressRelease->annexure_1_1_complexity as $row)
                                <tr>
                                    <td class="p-2 border border-black">{{ $row['instrument'] ?? '' }}</td>
                                    <td class="p-2 border border-black">{{ $row['level'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-sm italic mt-1">ACER has classified instruments rated by it based on complexity, and a note
                        thereon is available at
                        www.acerratings.com</p>
                </div>
            @endif

            <!-- ANNEXURE 2: Instrument Details -->
            @if(!empty($pressRelease->annexure_2_instruments) && is_array($pressRelease->annexure_2_instruments) && count($pressRelease->annexure_2_instruments) > 0 && !empty($pressRelease->annexure_2_instruments[0]['name']))
                <div class="mb-4">
                    <h4 class="font-bold text-black text-lg">Annexure - 2: Instrument/Facility Details</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse border border-black">
                            <thead class="bg-gray-100 text-black text-center font-bold">
                                <tr>
                                    <th class="p-2 border border-black">Name of Facility/<br>/Security</th>
                                    <th class="p-2 border border-black">ISIN</th>
                                    <th class="p-2 border border-black">Date of<br>Issuance</th>
                                    <th class="p-2 border border-black">Coupon<br>Rate/<br>Interest<br>Rate</th>
                                    <th class="p-2 border border-black">Maturity<br>Date</th>
                                    <th class="p-2 border border-black">Size of<br>Facility<br>(Rs.<br>Crore)</th>
                                    <th class="p-2 border border-black">Listing Status<br>(Listed/ unlisted/<br>Proposed to
                                        be<br>listed)
                                    </th>
                                    <th class="p-2 border border-black">Rating/<br>Outlook</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressRelease->annexure_2_instruments as $row)
                                    <tr>
                                        <td class="p-2 border border-black font-medium">{{ $row['name'] ?? '' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['isin'] ?? '-' }}</td>
                                        <td class="p-2 border border-black text-center">
                                            {{ !empty($row['issuance_date']) ? \Carbon\Carbon::parse($row['issuance_date'])->format('d-M-Y') : '-' }}
                                        </td>
                                        <td class="p-2 border border-black text-center">{{ $row['coupon'] ?? '-' }}</td>
                                        <td class="p-2 border border-black text-center">
                                            {{ !empty($row['maturity']) ? \Carbon\Carbon::parse($row['maturity'])->format('d-M-Y') : '-' }}
                                        </td>
                                        <td class="p-2 border border-black text-center">{{ $row['size'] ?? '-' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['listing'] ?? '-' }}</td>
                                        <td class="p-2 border border-black text-center">{{ $row['rating'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- ANNEXURE 3: Lender Details -->
            @if(!empty($pressRelease->annexure_3_lenders) && is_array($pressRelease->annexure_3_lenders) && count($pressRelease->annexure_3_lenders) > 0 && !empty($pressRelease->annexure_3_lenders[0]['name']))
                <div class="mb-10">
                    <h4 class="font-bold text-black text-lg">Annexure - 3: Facility-wise lender details</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse border border-black">
                            <thead class="bg-gray-100 text-black">
                                <tr>
                                    <th class="p-3 border border-black">Bank Name</th>
                                    <th class="p-3 border border-black">Facility Name</th>
                                    <th class="p-3 border border-black text-right">Amount (INR crore)</th>
                                    <th class="p-3 border border-black text-center">Rating/ Outlook/ Watch</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressRelease->annexure_3_lenders as $lender)
                                    @if(!empty($lender['facilities']) && count($lender['facilities']) > 0)
                                        @foreach($lender['facilities'] as $index => $fac)
                                            <tr>
                                                @if($index === 0)
                                                    <td class="p-3 border border-black font-semibold align-top bg-gray-50/50"
                                                        rowspan="{{ count($lender['facilities']) }}">
                                                        {{ $lender['name'] ?? 'Bank/Lender' }}
                                                    </td>
                                                @endif
                                                <td class="p-3 border border-black">{{ $fac['facility'] ?? '' }}</td>
                                                <td class="p-3 border border-black text-right">{{ $fac['amount'] ?? '' }}</td>
                                                <td class="p-3 border border-black text-center font-medium">{{ $fac['rating'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="p-3 border border-black font-semibold align-top bg-gray-50/50">
                                                {{ $lender['name'] ?? 'Bank/Lender' }}
                                            </td>
                                            <td class="p-3 border border-black"></td>
                                            <td class="p-3 border border-black text-right"></td>
                                            <td class="p-3 border border-black text-center font-medium"></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- ANNEXURE 4: Covenant Details -->
            @if($pressRelease->ann4_covenants)
                <div class="mb-6">
                    <h4 class="font-bold text-black text-lg">Annexure 4: Detailed explanation of covenants of the rated
                        Security/facilities:</h4>
                    <div class="text-black prose max-w-none mt-2">
                        {!! $pressRelease->ann4_covenants !!}
                    </div>
                </div>
            @endif

            <!-- ANNEXURE 5: FSR List -->
            @if($pressRelease->ann5_fsr_list)
                <div class="mb-6">
                    <h4 class="font-bold text-black text-lg">Annexure 5: List of activities under the purview of SEBI and other
                        Financial Sector Regulators (FSR):</h4>
                    <div class="text-black prose max-w-none mt-2">
                        {!! $pressRelease->ann5_fsr_list !!}
                    </div>
                </div>
            @endif

            <!-- ANNEXURE 6: Entities Consolidated -->
            @if(!empty($pressRelease->ann6_entities_consolidated) && is_array($pressRelease->ann6_entities_consolidated) && count($pressRelease->ann6_entities_consolidated) > 0 && !empty($pressRelease->ann6_entities_consolidated[0]['name']))
                <div class="mb-6">
                    <h4 class="font-bold text-black text-lg">Annexure 6: List of companies considered for
                        consolidated/Combined analysis:</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse border border-black">
                            <thead class="bg-gray-100 text-black">
                                <tr>
                                    <th class="p-2 border border-black">Entity Name</th>
                                    <th class="p-2 border border-black text-center">Extent of Consolidation</th>
                                    <th class="p-2 border border-black text-center">Rationale for Consolidation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressRelease->ann6_entities_consolidated as $entity)
                                    @if(!empty($entity['name']))
                                        <tr>
                                            <td class="p-2 border border-black font-medium">{{ $entity['name'] }}</td>
                                            <td class="p-2 border border-black text-center">{{ $entity['extent'] ?? '' }}</td>
                                            <td class="p-2 border border-black text-center">{{ $entity['rationale'] ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <hr class="my-4 border-gray-200">

            <!-- CONTACTS -->
            @if(!empty($pressRelease->analytical_contacts) && is_array($pressRelease->analytical_contacts) && count($pressRelease->analytical_contacts) > 0 && !empty($pressRelease->analytical_contacts[0]['name']))
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-black">Analytical Contacts</h3>
                    <div class="flex flex-col gap-4">
                        @foreach($pressRelease->analytical_contacts as $contact)
                            @if(!empty($contact['name']))
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left border-collapse border border-black">
                                        <tbody>
                                            <tr>
                                                <td class="p-2 border border-black w-1/3">
                                                    {{ $contact['designation'] ?? 'Analyst Name' }}
                                                </td>
                                                <td class="p-2 border border-black w-2/3">{{ $contact['name'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 border border-black">Official Contact Number</td>
                                                <td class="p-2 border border-black">{{ $contact['phone'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 border border-black">Official email ID</td>
                                                <td class="p-2 border border-black">
                                                    @if(!empty($contact['email']))
                                                        <a href="mailto:{{ $contact['email'] }}"
                                                            class="text-blue-600 hover:underline">{{ $contact['email'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Disclaimer -->
            <div class="mb-10">
                <h3 class="text-xl font-bold text-black">Disclaimer</h3>
                <p class="mb-4">ACER Credit Rating Private Limited (ACER) is engaged in the business of providing credit
                    ratings and
                    other permitted services and does not provide investment advice or recommendations, directly or
                    indirectly, with respect to any securities. Ratings are subject to ongoing surveillance, revision or
                    withdrawal, as and when warranted.</p>
                <p class="mb-4">Information used in assigning ratings has been obtained from sources believed to be
                    reliable, including
                    the rated entity; however, such information has not been independently audited or verified by ACER.
                    While reasonable care has been exercised to ensure that the information contained herein is true and
                    fair, it is provided “as is”. ACER does not make any representation, warranty of any kind, or guarantee
                    the accuracy, adequacy, suitability or completeness of any information or its fitness for a particular
                    purpose.</p>
                <p class="mb-4">All ratings and related analyses are statements of opinion, and ACER shall not be liable
                    for any losses,
                    direct or indirect, arising from use of this publication or its contents. Users are advised to exercise
                    their own judgment and due diligence before making any decision based on the ratings.</p>
                <h3 class="text-xl font-bold text-black">About ACER</h3>
                <p class="mb-4">For more information and the definition of ratings, please visit www.acerratings.com.</p>
            </div>
        </div>
    </div>
@endsection