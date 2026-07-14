<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of all documents (restricted documents visible but not downloadable).
     */
    public function index(Request $request)
    {
        $query = Document::with(['category', 'subCategory', 'creator']);

        // Apply filters
        if ($request->filled('regulator')) {
            $query->where('regulator', $request->regulator);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->get();

        // Get category IDs that have documents
        $categoryIds = $documents->pluck('category_id')->unique()->filter();

        // Get categories with their sub-categories (only those that have documents)
        $categories = DocCategory::whereNull('parent_id')
            ->whereIn('id', $categoryIds)
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('public.documents.index', compact('documents', 'categories'));
    }

    /**
     * Display the specified document (restricted documents can be viewed but not downloaded).
     */
    public function show(Document $document)
    {
        $document->load(['category', 'subCategory']);
        return view('public.documents.show', compact('document'));
    }

    /**
     * Download document file
     */
    public function download(Document $document)
    {
        // Ensure document is public
        if ($document->isRestricted()) {
            abort(403, 'Access denied. This document is restricted.');
        }

        return Storage::disk('public')->download($document->file_path);
    }
}
