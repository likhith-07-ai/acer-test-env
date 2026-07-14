# Press Release Module Development Specification

## 1. Overview
The objective is to develop a robust "Press Release" module for the ACER Ratings website. This involves creating a backend management interface for comprehensive data entry based on the provided field specifications, and integrating it into the frontend `media-press.blade.php` to display dynamic listings. Clicking on a press release item from the list will open its detailed semantic HTML view in a separate window.

## 2. Backend Module Development (CMS)
A new entity/module (e.g., `PressRelease`) will be created in the backend to manage all Press Release data. The form will capture the fields specified in the *Press Release - Field Specification.pdf*.

### Key Form Sections & Fields
1. **Header Information**: Date, City, Company Name, Headline.
2. **Rating Action Table**: Tabular rating actions.
3. **Analytical Approach**: General approach description.
4. **Brief Summary / Detailed Rationale**: Rich text detailed overview.
5. **Key Rating Drivers**: Includes Strengths and Weaknesses.
6. **Liquidity & Rating Sensitivities**: Positive and Negative sensitivities.
7. **About the Company**: Description and Strategic Business Segments (`company_segments_table` dynamic table).
8. **Key Financial Indicators**: Dynamic columns (`fy_columns`) for Financial Years.
9. **Status & Other Information**: Status of non-cooperation and other miscellaneous info.
10. **Dynamic Annexures**:
    - **Annexure 1 (Rating History)**: Dynamic rows for multi-year historical ratings.
    - **Annexure 1.1 (Complexity Levels)**: Dynamic rows for instrument complexity.
    - **Annexure 2 (Instrument / Facility Details)**: Rows detailing facility name, ISIN, issuance, etc.
    - **Annexure 3 (Facility-wise Lender Details)**: Grouping by lender + related facilities.
    - **Annexure 4 (Covenants) & Annexure 5 (FSR List)**: Rich text descriptions.
    - **Annexure 6 (Entities Consolidated)**: Rows for partial/full consolidated entities.
11. **Applicable Rating Criteria & Analytical Contacts**: External/Internal PDF links and Analyst contact details.
12. **Disclaimer**: Static boilerplate text.

**Data Storage Strategy**: 
Since the final output is strongly tied to a single-document layout, the backend will serialize these dynamic tables and repeating elements as `JSON` structures within the database, and possibly compile the full `final_html_output` via a dedicated view template for faster readout on the frontend.

---

## 3. Frontend Listing Updates
**Target File:** `/Applications/XAMPP/xamppfiles/htdocs/acer-ratings/resources/views/public/media-press.blade.php` (Lines 49 - 106)

Currently, the Blade file is holding static "Coming Soon" cards. We need to implement a variable loop (e.g., `$pressReleases`) to iterate over published records.

**Proposed Integration:**
```blade
<!-- Cards Wrapper (Flexbox) -->
<div class="flex flex-wrap justify-center gap-6 lg:gap-8 lg:gap-y-12">
    @forelse($pressReleases as $pressRelease)
    <div class="flex flex-col gap-[24px] md:gap-[32px] group relative bg-white rounded-2xl p-4 lg:p-[1.5rem] transform transition-all duration-300 hover:-translate-y-2 hover:shadow-lg border border-quaternary-100 hover:border-primary-300 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1.333rem)]">
        <div>
            <!-- Icon -->
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary rounded-[0.75rem] flex items-center justify-center mb-4 transition-colors duration-300">
                <i class="acericon-specker text-xl sm:text-2xl text-white"></i>
            </div>
            <!-- Title -->
            <h4 class="text-[1.125rem] md:text-[1.5rem] font-bold text-quaternary">
                {{ $pressRelease->headline ?? 'Press Release' }}
            </h4>
            <!-- Description -->
            <p class="mt-2 text-gray-600 line-clamp-3">
                {{ Str::limit(strip_tags($pressRelease->brief_summary), 150) }}
            </p>
        </div>
        <!-- Button: Opens Data in separate HTML window -->
        <a href="{{ route('front.press-releases.show', $pressRelease->id) }}" target="_blank" class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-sm md:text-base font-medium transition-all duration-300 bg-primary hover:brightness-110 hover:shadow-lg w-full mt-auto">
            Read More <i class="ri-arrow-right-line"></i>
        </a>
    </div>
    @empty
    <div class="w-full text-center text-gray-500 py-8">
        No press releases available at the moment.
    </div>
    @endforelse
</div>
```

---

## 4. Frontend Detailed HTML View (Separate Window)
A new controller method must be created to serve the "detailed window" HTML format of the press release when `front.press-releases.show` is requested.

**Example Controller Implementation:**
```php
public function show($id) {
    // Fetch the detailed press release data
    $pressRelease = PressRelease::findOrFail($id);
    
    // Return a standalone view specifically styled for the HTML Press Release format
    return view('public.press-release-detail', compact('pressRelease'));
}
```

**Detail View `press-release-detail.blade.php`:**
This layout will **NOT** use the standard website header/footer. It will be a self-contained HTML layout matching the ACER/CRISIL press release document format containing all 22 required sections dynamically generated based on the backend data.
