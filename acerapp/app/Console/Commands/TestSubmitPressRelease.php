<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\PressReleaseController;
use Exception;

class TestSubmitPressRelease extends Command
{
    protected $signature = 'test:pressrelease';
    protected $description = 'Test Press Release Submission';

    public function handle()
    {
        try {
            $datasets = [];

            // Sample Data 1
            $datasets[] = [
                'headline' => '"ACER AA / Stable" assigned to Non Convertible Debentures of Tata Projects Limited',
                'company_name' => 'Tata Projects Limited',
                'date' => '2026-03-02',
                'city' => 'Mumbai',

                'rating_action_table' => json_encode([
                    [
                        'instrument_name' => 'Rs.500 Crore Non Convertible Debentures',
                        'amount_inr' => '500.00',
                        'current_rating' => 'ACER AA/Stable',
                        'rating_action' => 'Assigned',
                        'regulator' => 'SEBI'
                    ],
                    [
                        'instrument_name' => 'Rs.1500 Crore Bank Facilities',
                        'amount_inr' => '1500.00',
                        'current_rating' => 'ACER AA/Stable',
                        'rating_action' => 'Reaffirmed',
                        'regulator' => 'RBI'
                    ]
                ]),

                'unsupported_rating' => 'ACER A+',

                'analytical_approach' => '<p>ACER has taken a consolidated view of Tata Projects Limited (TPL) and its subsidiaries/joint ventures due to strong operational and financial linkages.</p><p>The ratings draw comfort from TPL’s established track record in Engineering, Procurement, and Construction (EPC) business, strong parentage as part of the Tata Group, and a robust order book providing revenue visibility.</p>',

                'brief_summary' => '<p>The rating assignment reflects TPL’s strong parentage (Tata Group) and its established position in the domestic EPC market. TPL continues to maintain a healthy and diversified order book. The rating also factors in TPL\'s adequate liquidity profile, supported by stable working capital management and financial flexibility associated with the Tata Group.</p><p>These strengths are partially offset by the inherently working capital-intensive nature of the EPC business, exposure to project execution risks, and susceptibility to volatility in raw material prices. The moderate financial risk profile, characterized by moderately high leverage, remains a constraint.</p>',

                'strengths' => json_encode([
                    [
                        'title' => 'Strong Parentage and Strategic Importance',
                        'body' => '<p>TPL is strategically important to the Tata Group as it represents the group\'s presence in the EPC sector. TPL benefits from the operational and financial flexibility of being a Tata Group company, and has a strong board and proven management team.</p>'
                    ],
                    [
                        'title' => 'Robust Order Book Providing Revenue Visibility',
                        'body' => '<p>As of December 2025, TPL has an unexecuted order book of around INR 45,000 crores, translating to an order book to revenue ratio of over 3x, providing strong medium-term revenue visibility. The order book is well-diversified across sectors including transportation, urban infrastructure, industrial, and power.</p>'
                    ],
                    [
                        'title' => 'Established Track Record in Executing Complex Projects',
                        'body' => '<p>TPL has successfully completed several marquee infrastructure projects in India, demonstrating strong execution capabilities. The company’s focus on selecting technologically intensive and high-margin projects mitigates competition to an extent.</p>'
                    ]
                ]),

                'weaknesses' => json_encode([
                    [
                        'title' => 'Moderate Financial Risk Profile',
                        'body' => '<p>Given the significant scaling up of operations, reliance on external debt and non-fund based limits has increased. The capital structure is leveraged with Total Outside Liabilities to Tangible Net Worth (TOL/TNW) estimated to remain moderately high.</p>'
                    ],
                    [
                        'title' => 'Working Capital Intensive Operations',
                        'body' => '<p>The EPC business involves substantial working capital requirements due to milestone-based billing, holdbacks, and retention money. However, TPL\'s working capital cycle is expected to remain managed through back-to-back arrangements with sub-contractors and advances from clients.</p>'
                    ],
                    [
                        'title' => 'Project Execution Risks and Raw Material Price Volatility',
                        'body' => '<p>The operations are exposed to typical project execution risks, including delays in land acquisition, regulatory clearances, and funding from clients. While mostly protected by price escalation clauses, some fixed-price contracts remain exposed to raw material price volatility.</p>'
                    ]
                ]),

                'liquidity' => 'Adequate',
                'liquidity_body' => '<p>TPL’s liquidity is considered adequate, supported by healthy cash flow from operations, unencumbered cash and bank balances of ~INR 1,200 crore as of December 2025, and high financial flexibility due to its parentage. The average utilization of working capital limits over the last 12 months has been around 75%, providing sufficient headroom.</p>',

                'positive_sensitivities' => json_encode([
                    ['text' => 'Significant improvement in profitability margins on a sustained basis.'],
                    ['text' => 'Substantial reduction in debt levels leading to improvement in debt coverage indicators.'],
                    ['text' => 'Improvement in the working capital cycle reducing reliance on external debt.']
                ]),

                'negative_sensitivities' => json_encode([
                    ['text' => 'Significant delays in project execution impacting revenue booking and profitability.'],
                    ['text' => 'Deterioration in working capital management leading to stretched cash flows.'],
                    ['text' => 'Large debt-funded capex or investments negatively impacting the financial risk profile.']
                ]),

                'about_company_body' => '<p>Tata Projects Limited (TPL) is one of the fastest growing and most admired infrastructure companies in India. It has expertise in executing large and complex urban and industrial infrastructure projects. TPL operates through its strategically aligned business units: Urban Infrastructure, Core Infra, and Industrial Systems.</p>',

                'company_segments_table' => json_encode([
                    ['group' => 'Urban Infrastructure', 'description' => 'Metro rails, airports, smart cities, and public spaces.'],
                    ['group' => 'Core Infra', 'description' => 'Power generation, transmission and distribution, roads, and bridges.'],
                    ['group' => 'Industrial Systems', 'description' => 'Hydrocarbon, chemical, and metal plant constructions.']
                ]),

                'financials_basis' => 'Consolidated',
                'fy_columns' => json_encode([
                    ['label' => 'FY2023 (Audited)', 'revenue' => '10200.50', 'ebitda' => '850.25', 'ebitda_margin' => '8.33', 'coverage' => '2.50', 'leverage' => '3.80', 'pat_margin' => '3.10'],
                    ['label' => 'FY2024 (Audited)', 'revenue' => '13500.00', 'ebitda' => '1200.00', 'ebitda_margin' => '8.88', 'coverage' => '3.10', 'leverage' => '3.10', 'pat_margin' => '3.75'],
                    ['label' => 'FY2025 (Projected)', 'revenue' => '17000.00', 'ebitda' => '1650.00', 'ebitda_margin' => '9.70', 'coverage' => '3.50', 'leverage' => '2.80', 'pat_margin' => '4.20'],
                ]),

                'financials_source' => 'Issuer Data, ACER Ratings Analysis',

                'non_cooperation_status' => '<p>Not Applicable</p>',
                'other_information' => '<p>Not Applicable</p>',

                'annexure_1_rating_history' => json_encode([
                    ['instrument' => 'Non Convertible Debentures', 'type' => 'Long-term', 'limits' => '500.00', 'current_rating' => 'ACER AA/Stable'],
                    ['instrument' => 'Term Loan', 'type' => 'Long-term', 'limits' => '800.00', 'current_rating' => 'ACER AA/Stable'],
                    ['instrument' => 'Cash Credit', 'type' => 'Long-term', 'limits' => '700.00', 'current_rating' => 'ACER AA/Stable']
                ]),

                'annexure_1_1_complexity' => json_encode([
                    ['instrument' => 'Non Convertible Debentures', 'level' => 'Simple'],
                    ['instrument' => 'Bank Facilities', 'level' => 'Simple']
                ]),

                'annexure_2_instruments' => json_encode([
                    ['name' => 'NCD Issue I', 'isin' => 'INE234A07111', 'size' => '250.00', 'issuance_date' => '2025-06-15', 'coupon' => '8.50%', 'maturity' => '2030-06-15', 'listing' => 'Listed', 'rating' => 'ACER AA/Stable'],
                    ['name' => 'NCD Issue II', 'isin' => 'INE234A07129', 'size' => '250.00', 'issuance_date' => '2025-10-20', 'coupon' => '8.75%', 'maturity' => '2032-10-20', 'listing' => 'Listed', 'rating' => 'ACER AA/Stable']
                ]),

                'annexure_3_lenders' => json_encode([
                    [
                        'name' => 'State Bank of India',
                        'facilities' => [
                            ['facility' => 'Term Loan', 'amount' => '400.00', 'rating' => 'ACER AA/Stable'],
                            ['facility' => 'Cash Credit', 'amount' => '300.00', 'rating' => 'ACER AA/Stable']
                        ]
                    ],
                    [
                        'name' => 'HDFC Bank',
                        'facilities' => [
                            ['facility' => 'Term Loan', 'amount' => '200.00', 'rating' => 'ACER AA/Stable'],
                            ['facility' => 'Cash Credit', 'amount' => '250.00', 'rating' => 'ACER AA/Stable']
                        ]
                    ],
                    [
                        'name' => 'Axis Bank',
                        'facilities' => [
                            ['facility' => 'Term Loan', 'amount' => '200.00', 'rating' => 'ACER AA/Stable'],
                            ['facility' => 'Cash Credit', 'amount' => '150.00', 'rating' => 'ACER AA/Stable']
                        ]
                    ]
                ]),

                'ann4_covenants' => '<p>Standard financial covenants including maintaining DSCR above 1.2x and Net Debt/EBITDA below 3.5x.</p>',
                'ann5_fsr_list' => '<p>Refer to Annexure 6 for consolidated entities.</p>',

                'ann6_entities_consolidated' => json_encode([
                    ['name' => 'Artesian Water Projects Limited', 'extent' => 'Full', 'rationale' => 'Wholly Owned Subsidiary'],
                    ['name' => 'Tata Projects Infrastructure Limited', 'extent' => 'Full', 'rationale' => 'Significant control and operational holding']
                ]),

                'applicable_criteria' => json_encode([
                    ['name' => 'Rating Methodology for Infrastructure Sector', 'url' => 'https://acerratings.com/criteria/infrastructure'],
                    ['name' => 'Consolidation and Parent/Group Support', 'url' => 'https://acerratings.com/criteria/consolidation']
                ]),

                'analytical_contacts' => json_encode([
                    ['name' => 'Vikram Sharma', 'designation' => 'Lead Analyst', 'email' => 'vikram.sharma@acerratings.com', 'phone' => '+91-22-12345678'],
                    ['name' => 'Priya Nair', 'designation' => 'Director - Ratings', 'email' => 'priya.nair@acerratings.com', 'phone' => '+91-22-87654321'],
                    ['name' => 'Rahul Desai', 'designation' => 'Chief Rating Officer', 'email' => 'rahul.desai@acerratings.com', 'phone' => '+91-22-99998888']
                ])
            ];

            // Sample Data 2
            $datasets[] = [
                'headline' => '"ACER AAA / Stable" reaffirmed for Bank Facilities of Reliance Industries Limited',
                'company_name' => 'Reliance Industries Limited',
                'date' => '2026-03-05',
                'city' => 'Mumbai',

                'rating_action_table' => json_encode([
                    [
                        'instrument_name' => 'Rs.5000 Crore Bank Facilities',
                        'amount_inr' => '5000.00',
                        'current_rating' => 'ACER AAA/Stable',
                        'rating_action' => 'Reaffirmed',
                        'regulator' => 'RBI'
                    ]
                ]),

                'unsupported_rating' => 'ACER AAA',

                'analytical_approach' => '<p>ACER has taken a consolidated view of Reliance Industries Limited and its subsidiaries.</p>',

                'brief_summary' => '<p>The rating reaffirmation reflects Reliance Industries Limited\'s strong market position across its diversified business segments including oil to chemicals, retail, and digital services.</p>',

                'strengths' => json_encode([
                    [
                        'title' => 'Highly Diversified Operations',
                        'body' => '<p>Strong market position in O2C, Retail, and Telecommunications segments providing stability in resilient margin performance.</p>'
                    ]
                ]),

                'weaknesses' => json_encode([
                    [
                        'title' => 'Exposure to Volatile Refining Margins',
                        'body' => '<p>The O2C segment is exposed to global refining margin volatility, though this is mitigated by high complexity of its refineries.</p>'
                    ]
                ]),

                'liquidity' => 'Strong',
                'liquidity_body' => '<p>RIL maintains strong liquidity with substantial cash balances, strong cash flow generation from operations, and exceptional financial flexibility in the market.</p>',

                'positive_sensitivities' => json_encode([
                    ['text' => 'Not applicable given the highest rating.']
                ]),

                'negative_sensitivities' => json_encode([
                    ['text' => 'Significant debt-funded acquisition negatively impacting the financial risk profile.']
                ]),

                'about_company_body' => '<p>Reliance Industries Limited is India\'s largest private sector company with diverse businesses across energy, petrochemicals, natural gas, retail, telecommunications, mass media, and textiles.</p>',

                'company_segments_table' => json_encode([
                    ['group' => 'O2C', 'description' => 'Refining and petrochemicals.'],
                    ['group' => 'Retail', 'description' => 'Largest retail network in India across grocery, consumer electronics, and fashion.'],
                    ['group' => 'Digital Services', 'description' => 'Broadband services and telecommunications network provided through Jio.']
                ]),

                'financials_basis' => 'Consolidated',
                'fy_columns' => json_encode([
                    ['label' => 'FY2024 (Audited)', 'revenue' => '900000.00', 'ebitda' => '170000.00', 'ebitda_margin' => '18.88', 'coverage' => '6.10', 'leverage' => '1.50', 'pat_margin' => '8.75'],
                    ['label' => 'FY2025 (Projected)', 'revenue' => '980000.00', 'ebitda' => '185000.00', 'ebitda_margin' => '18.87', 'coverage' => '6.50', 'leverage' => '1.40', 'pat_margin' => '9.00'],
                ]),

                'financials_source' => 'Issuer Data, ACER Ratings Analysis',
                'non_cooperation_status' => '<p>Not Applicable</p>',
                'other_information' => '<p>Not Applicable</p>',

                'annexure_1_rating_history' => json_encode([
                    ['instrument' => 'Bank Facilities', 'type' => 'Long-term', 'limits' => '5000.00', 'current_rating' => 'ACER AAA/Stable'],
                ]),

                'annexure_1_1_complexity' => json_encode([
                    ['instrument' => 'Bank Facilities', 'level' => 'Simple']
                ]),

                'annexure_2_instruments' => json_encode([
                    ['name' => 'Bank Loan', 'isin' => 'NA', 'size' => '5000.00', 'issuance_date' => 'NA', 'coupon' => 'NA', 'maturity' => 'NA', 'listing' => 'Unlisted', 'rating' => 'ACER AAA/Stable'],
                ]),

                'annexure_3_lenders' => json_encode([
                    [
                        'name' => 'Multiple Banks',
                        'facilities' => [
                            ['facility' => 'Term Loan', 'amount' => '5000.00', 'rating' => 'ACER AAA/Stable'],
                        ]
                    ]
                ]),

                'ann4_covenants' => '<p>Standard financial covenants.</p>',
                'ann5_fsr_list' => '<p>Not Applicable.</p>',
                'ann6_entities_consolidated' => json_encode([]),
                'applicable_criteria' => json_encode([
                    ['name' => 'Rating Methodology for Manufacturing Companies', 'url' => 'https://acerratings.com/criteria/manufacturing'],
                ]),
                'analytical_contacts' => json_encode([
                    ['name' => 'Vikram Sharma', 'designation' => 'Lead Analyst', 'email' => 'vikram.sharma@acerratings.com', 'phone' => '+91-22-12345678'],
                ])
            ];

            // Sample Data 3
            $datasets[] = [
                'headline' => '"ACER A+ / Positive" assigned to Tech Solutions Private Limited',
                'company_name' => 'Tech Solutions Private Limited',
                'date' => '2026-03-01',
                'city' => 'Bengaluru',

                'rating_action_table' => json_encode([
                    [
                        'instrument_name' => 'Rs.200 Crore Bank Facilities',
                        'amount_inr' => '200.00',
                        'current_rating' => 'ACER A+/Positive',
                        'rating_action' => 'Assigned',
                        'regulator' => 'RBI'
                    ]
                ]),

                'unsupported_rating' => 'ACER A',

                'analytical_approach' => '<p>Standalone analytical approach.</p>',

                'brief_summary' => '<p>The rating assigned to Tech Solutions Private Limited reflects its growing market presence and expertise in providing specialized IT services globally.</p>',

                'strengths' => json_encode([
                    [
                        'title' => 'Experienced Management',
                        'body' => '<p>The promoters have over two decades of experience in the IT sector, contributing to strong client relationships and deep technical expertise.</p>'
                    ]
                ]),

                'weaknesses' => json_encode([
                    [
                        'title' => 'Customer Concentration Risk',
                        'body' => '<p>Top 5 clients contribute to ~60% of total revenue. However, long-standing relationships with these clients provide some comfort.</p>'
                    ]
                ]),

                'liquidity' => 'Adequate',
                'liquidity_body' => '<p>Liquidity is supported by steady cash accruals and moderate reliance on fund-based working capital limits.</p>',

                'positive_sensitivities' => json_encode([
                    ['text' => 'Significant diversification of the client base and geographic presence.']
                ]),

                'negative_sensitivities' => json_encode([
                    ['text' => 'Decline in operating margins to below 15% due to high attrition or lower billing rates.']
                ]),

                'about_company_body' => '<p>Tech Solutions Private Limited provides specialized IT consulting services, software custom development, and digital transformation solutions to global enterprises.</p>',

                'company_segments_table' => json_encode([
                    ['group' => 'IT Consulting', 'description' => 'Architecture, digital strategy and advisory services.']
                ]),

                'financials_basis' => 'Standalone',
                'fy_columns' => json_encode([
                    ['label' => 'FY2024 (Audited)', 'revenue' => '800.00', 'ebitda' => '160.00', 'ebitda_margin' => '20.00', 'coverage' => '5.00', 'leverage' => '1.20', 'pat_margin' => '12.00'],
                    ['label' => 'FY2025 (Projected)', 'revenue' => '950.00', 'ebitda' => '195.00', 'ebitda_margin' => '20.52', 'coverage' => '5.20', 'leverage' => '1.00', 'pat_margin' => '12.50'],
                ]),

                'financials_source' => 'Issuer Data',
                'non_cooperation_status' => '<p>Not Applicable</p>',
                'other_information' => '<p>Not Applicable</p>',

                'annexure_1_rating_history' => json_encode([
                    ['instrument' => 'Bank Facilities', 'type' => 'Long-term', 'limits' => '200.00', 'current_rating' => 'ACER A+/Positive'],
                ]),

                'annexure_1_1_complexity' => json_encode([
                    ['instrument' => 'Bank Facilities', 'level' => 'Simple']
                ]),

                'annexure_2_instruments' => json_encode([
                    ['name' => 'Cash Credit', 'isin' => 'NA', 'size' => '200.00', 'issuance_date' => 'NA', 'coupon' => 'NA', 'maturity' => 'NA', 'listing' => 'Unlisted', 'rating' => 'ACER A+/Positive'],
                ]),

                'annexure_3_lenders' => json_encode([
                    [
                        'name' => 'ICICI Bank',
                        'facilities' => [
                            ['facility' => 'Cash Credit', 'amount' => '200.00', 'rating' => 'ACER A+/Positive'],
                        ]
                    ]
                ]),

                'ann4_covenants' => '<p>Standard industry covenants apply.</p>',
                'ann5_fsr_list' => '<p>Not Applicable.</p>',
                'ann6_entities_consolidated' => json_encode([]),
                'applicable_criteria' => json_encode([
                    ['name' => 'Rating Methodology for IT Sector', 'url' => 'https://acerratings.com/criteria/it'],
                ]),
                'analytical_contacts' => json_encode([
                    ['name' => 'Priya Nair', 'designation' => 'Director - Ratings', 'email' => 'priya.nair@acerratings.com', 'phone' => '+91-22-87654321'],
                ])
            ];

            // Sample Data 4
            $datasets[] = [
                'headline' => '"ACER BBB / Stable" assigned to Term Loan of Green Energy Limited',
                'company_name' => 'Green Energy Limited',
                'date' => '2026-03-06',
                'city' => 'Delhi',

                'rating_action_table' => json_encode([
                    [
                        'instrument_name' => 'Rs.150 Crore Term Loan',
                        'amount_inr' => '150.00',
                        'current_rating' => 'ACER BBB/Stable',
                        'rating_action' => 'Assigned',
                        'regulator' => 'RBI'
                    ]
                ]),

                'unsupported_rating' => 'ACER BBB',

                'analytical_approach' => '<p>Standalone analytical approach.</p>',

                'brief_summary' => '<p>The rating assigned to Green Energy Limited reflects its established track record in renewable energy projects and long-term power purchase agreements.</p>',

                'strengths' => json_encode([
                    [
                        'title' => 'Long-term Power Purchase Agreements',
                        'body' => '<p>The company has long-term PPAs with state utilities, ensuring revenue visibility and stable cash flows.</p>'
                    ]
                ]),

                'weaknesses' => json_encode([
                    [
                        'title' => 'Exposure to Regulatory Risks',
                        'body' => '<p>Operations are vulnerable to regulatory changes in the renewable energy sector and state-level policy shifts.</p>'
                    ]
                ]),

                'liquidity' => 'Adequate',
                'liquidity_body' => '<p>Liquidity is supported by steady cash accruals and adequate debt service coverage.</p>',

                'positive_sensitivities' => json_encode([
                    ['text' => 'Significant capacity addition leading to higher revenue and profitability.']
                ]),

                'negative_sensitivities' => json_encode([
                    ['text' => 'Delays in receiving payments from state utilities.']
                ]),

                'about_company_body' => '<p>Green Energy Limited develops and operates solar and wind power projects across India.</p>',

                'company_segments_table' => json_encode([
                    ['group' => 'Renewable Energy', 'description' => 'Solar and Wind power generation.']
                ]),

                'financials_basis' => 'Standalone',
                'fy_columns' => json_encode([
                    ['label' => 'FY2024 (Audited)', 'revenue' => '300.00', 'ebitda' => '120.00', 'ebitda_margin' => '40.00', 'coverage' => '2.50', 'leverage' => '3.50', 'pat_margin' => '15.00'],
                    ['label' => 'FY2025 (Projected)', 'revenue' => '350.00', 'ebitda' => '140.00', 'ebitda_margin' => '40.00', 'coverage' => '2.80', 'leverage' => '3.00', 'pat_margin' => '16.50'],
                ]),

                'financials_source' => 'Issuer Data',
                'non_cooperation_status' => '<p>Not Applicable</p>',
                'other_information' => '<p>Not Applicable</p>',

                'annexure_1_rating_history' => json_encode([
                    ['instrument' => 'Term Loan', 'type' => 'Long-term', 'limits' => '150.00', 'current_rating' => 'ACER BBB/Stable'],
                ]),

                'annexure_1_1_complexity' => json_encode([
                    ['instrument' => 'Term Loan', 'level' => 'Simple']
                ]),

                'annexure_2_instruments' => json_encode([
                    ['name' => 'Term Loan', 'isin' => 'NA', 'size' => '150.00', 'issuance_date' => 'NA', 'coupon' => '9.00%', 'maturity' => '2035-03-31', 'listing' => 'Unlisted', 'rating' => 'ACER BBB/Stable'],
                ]),

                'annexure_3_lenders' => json_encode([
                    [
                        'name' => 'Punjab National Bank',
                        'facilities' => [
                            ['facility' => 'Term Loan', 'amount' => '150.00', 'rating' => 'ACER BBB/Stable'],
                        ]
                    ]
                ]),

                'ann4_covenants' => '<p>Standard industry covenants apply.</p>',
                'ann5_fsr_list' => '<p>Not Applicable.</p>',
                'ann6_entities_consolidated' => json_encode([]),
                'applicable_criteria' => json_encode([
                    ['name' => 'Rating Methodology for Power Sector', 'url' => 'https://acerratings.com/criteria/power'],
                ]),
                'analytical_contacts' => json_encode([
                    ['name' => 'Rahul Desai', 'designation' => 'Chief Rating Officer', 'email' => 'rahul.desai@acerratings.com', 'phone' => '+91-22-99998888'],
                ])
            ];

            // Sample Data 5
            $datasets[] = [
                'headline' => '"ACER AA- / Positive" upgraded for Fixed Deposits of Horizon Finance Limited',
                'company_name' => 'Horizon Finance Limited',
                'date' => '2026-03-08',
                'city' => 'Chennai',

                'rating_action_table' => json_encode([
                    [
                        'instrument_name' => 'Fixed Deposit Programme',
                        'amount_inr' => '1000.00',
                        'current_rating' => 'ACER AA-/Positive',
                        'rating_action' => 'Upgraded',
                        'regulator' => 'RBI'
                    ]
                ]),

                'unsupported_rating' => 'ACER A+',

                'analytical_approach' => '<p>Standalone analytical approach.</p>',

                'brief_summary' => '<p>The rating upgrade reflects Horizon Finance Limited\'s improved asset quality and robust capitalization levels.</p>',

                'strengths' => json_encode([
                    [
                        'title' => 'Healthy Capitalization',
                        'body' => '<p>The company maintains healthy capital adequacy ratio, well above the regulatory requirements.</p>'
                    ]
                ]),

                'weaknesses' => json_encode([
                    [
                        'title' => 'Geographical Concentration',
                        'body' => '<p>Operations are largely concentrated in Southern India, although expansion efforts are underway.</p>'
                    ]
                ]),

                'liquidity' => 'Strong',
                'liquidity_body' => '<p>Strong liquidity profile with structural positive mismatches across all buckets in the ALM.</p>',

                'positive_sensitivities' => json_encode([
                    ['text' => 'Material improvement in scale of operations while maintaining asset quality.']
                ]),

                'negative_sensitivities' => json_encode([
                    ['text' => 'Deterioration in asset quality leading to pressure on profitability.']
                ]),

                'about_company_body' => '<p>Horizon Finance Limited is a non-banking financial company primarily engaged in vehicle and housing finance.</p>',

                'company_segments_table' => json_encode([
                    ['group' => 'Financial Services', 'description' => 'Vehicle and housing finance.']
                ]),

                'financials_basis' => 'Standalone',
                'fy_columns' => json_encode([
                    ['label' => 'FY2024 (Audited)', 'revenue' => '1500.00', 'ebitda' => '900.00', 'ebitda_margin' => '60.00', 'coverage' => '3.00', 'leverage' => '4.50', 'pat_margin' => '25.00'],
                    ['label' => 'FY2025 (Projected)', 'revenue' => '1800.00', 'ebitda' => '1100.00', 'ebitda_margin' => '61.11', 'coverage' => '3.50', 'leverage' => '4.00', 'pat_margin' => '26.50'],
                ]),

                'financials_source' => 'Issuer Data',
                'non_cooperation_status' => '<p>Not Applicable</p>',
                'other_information' => '<p>Not Applicable</p>',

                'annexure_1_rating_history' => json_encode([
                    ['instrument' => 'Fixed Deposit Programme', 'type' => 'Long-term', 'limits' => '1000.00', 'current_rating' => 'ACER AA-/Positive'],
                ]),

                'annexure_1_1_complexity' => json_encode([
                    ['instrument' => 'Fixed Deposit Programme', 'level' => 'Simple']
                ]),

                'annexure_2_instruments' => json_encode([
                    ['name' => 'Fixed Deposit Programme', 'isin' => 'NA', 'size' => '1000.00', 'issuance_date' => 'NA', 'coupon' => 'Varies', 'maturity' => 'Varies', 'listing' => 'Unlisted', 'rating' => 'ACER AA-/Positive'],
                ]),

                'annexure_3_lenders' => json_encode([]),

                'ann4_covenants' => '<p>None.</p>',
                'ann5_fsr_list' => '<p>Not Applicable.</p>',
                'ann6_entities_consolidated' => json_encode([]),
                'applicable_criteria' => json_encode([
                    ['name' => 'Rating Methodology for NBFCs', 'url' => 'https://acerratings.com/criteria/nbfc'],
                ]),
                'analytical_contacts' => json_encode([
                    ['name' => 'Vikram Sharma', 'designation' => 'Lead Analyst', 'email' => 'vikram.sharma@acerratings.com', 'phone' => '+91-22-12345678'],
                ])
            ];

            foreach ($datasets as $index => $data) {
                $req = Request::create('/admin/press-releases', 'POST', $data);
                app(PressReleaseController::class)->store($req);
                $this->info("Press Release " . ($index + 1) . " Saved OK!");
            }
        } catch (\Throwable $e) {
            $this->error("Exception: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine());
        }
    }
}
