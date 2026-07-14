<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Document;
use App\Models\DocCategory;
use Illuminate\Http\Request;

class RegulatorController extends Controller
{
    /**
     * Display SEBI regulator page
     */
    public function sebi(Request $request)
    {
        return $this->renderRegulatorPage($request, 'SEBI', 'regulator.sebi');
    }

    /**
     * Display RBI regulator page
     */
    public function rbi(Request $request)
    {
        if (!config('app.show_rbi_section')) {
            abort(404);
        }
        return $this->renderRegulatorPage($request, 'RBI', 'regulator.rbi');
    }

    /**
     * Display Other FSR regulator page
     */
    public function otherFsr(Request $request)
    {
        return $this->renderRegulatorPage($request, 'OTHER', 'regulator.other-fsr');
    }

    /**
     * Common method to render regulator pages
     */
    private function renderRegulatorPage(Request $request, string $regulator, string $view)
    {
        // Get public policies only
        $policiesQuery = Policy::public();

        // Get public documents only (exclude restricted)
        $documentsQuery = Document::public()->with(['category', 'subCategory']);

        // Apply regulator filter
        if ($regulator !== 'ALL') {
            $documentsQuery->where('regulator', $regulator);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $policiesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%");
            });

            $documentsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $policies = $policiesQuery->latest()->get();
        $documents = $documentsQuery->latest()->get();

        // Get all parent categories
        $allCategories = DocCategory::whereNull('parent_id')
            ->with(['children'])
            ->orderBy('name')
            ->get();

        // Filter categories that have documents matching filters
        $categories = $allCategories->filter(function ($category) use ($documents) {
            $hasMainCategoryDocs = $documents->where('category_id', $category->id)->whereNull('sub_category_id')->count() > 0;
            $hasSubCategoryDocs = false;
            foreach ($category->children as $subCategory) {
                if ($documents->where('sub_category_id', $subCategory->id)->count() > 0) {
                    $hasSubCategoryDocs = true;
                    break;
                }
            }
            return $hasMainCategoryDocs || $hasSubCategoryDocs;
        })->values();

        // If AJAX request, return only documents HTML
        if ($request->ajax() || $request->wantsJson()) {
            $documentsHtml = view('components.documents-list-content', compact('documents', 'categories', 'regulator'))->render();
            $headingHtml = view('components.documents-heading', compact('regulator'))->render();

            return response()->json([
                'success' => true,
                'documents_html' => $documentsHtml,
                'heading_html' => $headingHtml,
                'regulator' => $regulator
            ]);
        }

        return view($view, compact('policies', 'documents', 'categories', 'regulator'));
    }
}
