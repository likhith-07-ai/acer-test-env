<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ResearchArticle;
use App\Models\ResearchCategory;
use Illuminate\Http\Request;

class ResearchArticleController extends Controller
{
    /**
     * Display a listing of public research articles
     */
    public function index(Request $request)
    {
        // Debug: Check current time
        $now = now();
        \Log::info('Current time in controller: ' . $now);

        // Build query step by step to debug
        $query = ResearchArticle::where('status', 'published')
            ->where('is_restricted', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now);

        \Log::info('After basic conditions - Count: ' . $query->count());

        $query->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        // Filter by category (only if category exists and is valid)
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $categoryExists = ResearchCategory::where('slug', $categorySlug)->exists();

            if ($categoryExists) {
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
                \Log::info('Category filter applied: ' . $categorySlug);
            } else {
                // If category doesn't exist, ignore the filter
                \Log::info('Category filter ignored - category does not exist: ' . $categorySlug);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
            \Log::info('Search filter applied: ' . $search);
        }

        $articles = $query->paginate(config('pagination.public_per_page'));
        $categories = ResearchCategory::active()->get();

        // Debug: Log articles count
        \Log::info('Research Articles Count: ' . $articles->count());
        \Log::info('Total Articles: ' . $articles->total());

        return view('public.research.index', compact('articles', 'categories'));
    }

    /**
     * Display the specified research article
     */
    public function show($slug)
    {
        $article = ResearchArticle::public()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags', 'media'])
            ->firstOrFail();

        // Increment views
        $article->incrementViews();

        // Get related articles
        $relatedArticles = ResearchArticle::public()
            ->where('id', '!=', $article->id)
            ->where(function ($query) use ($article) {
                if ($article->category_id) {
                    $query->where('category_id', $article->category_id);
                }
            })
            ->limit(3)
            ->get();

        return view('public.research.show', compact('article', 'relatedArticles'));
    }
}
