<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchArticle;
use App\Models\ResearchCategory;
use App\Models\ResearchTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResearchArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.view')) {
            abort(403, 'You do not have permission to view research articles.');
        }

        $query = ResearchArticle::with(['category', 'author', 'tags']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by restricted
        if ($request->filled('is_restricted')) {
            $query->where('is_restricted', $request->is_restricted);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Author can only see their own articles
        // Admin and Super Admin can see all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            $query->where('author_id', auth()->id());
        }

        $articles = $query->latest()->paginate(config('pagination.admin_per_page'))->appends($request->query());
        $categories = ResearchCategory::active()->get();

        return view('admin.research-articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.create')) {
            abort(403, 'You do not have permission to create research articles.');
        }

        $categories = ResearchCategory::active()->get();
        $tags = ResearchTag::all();
        return view('admin.research-articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.create')) {
            abort(403, 'You do not have permission to create research articles.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:research_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:research_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_restricted' => 'boolean',
            'status' => 'required|in:draft,submitted',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'title',
            'excerpt',
            'content',
            'category_id',
            'is_restricted',
            'status'
        ]);

        $data['author_id'] = auth()->id();

        // Generate unique slug
        $data['slug'] = ResearchArticle::generateUniqueSlug($request->title);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('research-articles', 'public');
        }

        $article = ResearchArticle::create($data);

        // Attach tags
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        // Save SEO meta data
        if ($request->filled('meta_description')) {
            $article->meta()->updateOrCreate(
                ['meta_key' => 'meta_description'],
                ['meta_value' => $request->meta_description]
            );
        }

        if ($request->filled('meta_keywords')) {
            $article->meta()->updateOrCreate(
                ['meta_key' => 'meta_keywords'],
                ['meta_value' => $request->meta_keywords]
            );
        }

        return redirect()->route('admin.research-articles.index')
            ->with('success', __('messages.success.research_article.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.view')) {
            abort(403, 'You do not have permission to view research articles.');
        }

        // Author can only view their own articles
        // Admin and Super Admin can view all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $researchArticle->author_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $researchArticle->load(['category', 'author', 'tags', 'media', 'meta']);
        return view('admin.research-articles.show', compact('researchArticle'));
    }

    /**
     * Preview article as it will appear on public site
     */
    public function preview(ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.view')) {
            abort(403, 'You do not have permission to preview research articles.');
        }

        // Author can only preview their own articles
        // Admin and Super Admin can preview all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $researchArticle->author_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $researchArticle->load(['category', 'author', 'tags', 'media', 'meta']);

        // Get related articles (only published ones for preview)
        $relatedArticles = ResearchArticle::public()
            ->where('id', '!=', $researchArticle->id)
            ->where(function ($query) use ($researchArticle) {
                if ($researchArticle->category_id) {
                    $query->where('category_id', $researchArticle->category_id);
                }
            })
            ->limit(3)
            ->get();

        // Use public view for preview - pass as 'article' variable
        $article = $researchArticle;
        return view('public.research.show', compact('article', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.edit')) {
            abort(403, 'You do not have permission to edit research articles.');
        }

        // Author can only edit their own articles
        // Admin and Super Admin can edit all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $researchArticle->author_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Allow editing published articles (removed restriction)

        $categories = ResearchCategory::active()->get();
        $tags = ResearchTag::all();
        $researchArticle->load(['tags', 'meta']);
        return view('admin.research-articles.edit', compact('researchArticle', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.edit')) {
            abort(403, 'You do not have permission to edit research articles.');
        }

        // Author can only update their own articles
        // Admin and Super Admin can update all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $researchArticle->author_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Allow updating published articles (removed restriction)

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:research_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:research_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_restricted' => 'boolean',
            'status' => 'required|in:draft,submitted',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'title',
            'excerpt',
            'content',
            'category_id',
            'is_restricted',
            'status'
        ]);

        // Update slug if title changed
        if ($researchArticle->title !== $request->title) {
            $data['slug'] = ResearchArticle::generateUniqueSlug($request->title, $researchArticle->id);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($researchArticle->featured_image) {
                Storage::disk('public')->delete($researchArticle->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('research-articles', 'public');
        }

        $researchArticle->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $researchArticle->tags()->sync($request->tags);
        } else {
            $researchArticle->tags()->detach();
        }

        // Update SEO meta data
        if ($request->filled('meta_description')) {
            $researchArticle->meta()->updateOrCreate(
                ['meta_key' => 'meta_description'],
                ['meta_value' => $request->meta_description]
            );
        } else {
            $researchArticle->meta()->where('meta_key', 'meta_description')->delete();
        }

        if ($request->filled('meta_keywords')) {
            $researchArticle->meta()->updateOrCreate(
                ['meta_key' => 'meta_keywords'],
                ['meta_value' => $request->meta_keywords]
            );
        } else {
            $researchArticle->meta()->where('meta_key', 'meta_keywords')->delete();
        }

        return redirect()->route('admin.research-articles.index')
            ->with('success', __('messages.success.research_article.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.delete')) {
            abort(403, 'You do not have permission to delete research articles.');
        }

        // Author can only delete their own articles
        // Admin and Super Admin can delete all articles
        if (auth()->user()->isAuthor() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $researchArticle->author_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Delete featured image
        if ($researchArticle->featured_image) {
            Storage::disk('public')->delete($researchArticle->featured_image);
        }

        $researchArticle->delete();

        return redirect()->route('admin.research-articles.index')
            ->with('success', __('messages.success.research_article.deleted'));
    }

    /**
     * Show approval queue
     */
    public function approvalQueue(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.approve')) {
            abort(403, 'You do not have permission to view approval queue.');
        }

        $query = ResearchArticle::pendingApproval()->with(['category', 'author']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest()->paginate(config('pagination.admin_per_page'));

        return view('admin.research-articles.approval-queue', compact('articles'));
    }

    /**
     * Approve an article
     */
    public function approve(Request $request, ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.approve')) {
            abort(403, 'You do not have permission to approve research articles.');
        }

        if (!auth()->user()->canApprove()) {
            abort(403, 'Unauthorized access. Approval privileges required.');
        }

        if ($researchArticle->status !== 'submitted') {
            return redirect()->back()
                ->with('error', __('messages.error.research_article.approve'));
        }

        $researchArticle->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.research-articles.approval-queue')
            ->with('success', __('messages.success.research_article.approved'));
    }

    /**
     * Reject an article
     */
    public function reject(Request $request, ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.approve')) {
            abort(403, 'You do not have permission to reject research articles.');
        }

        if (!auth()->user()->canApprove()) {
            abort(403, 'Unauthorized access. Approval privileges required.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($researchArticle->status !== 'submitted') {
            return redirect()->back()
                ->with('error', __('messages.error.research_article.reject'));
        }

        $researchArticle->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.research-articles.approval-queue')
            ->with('success', __('messages.success.research_article.rejected'));
    }

    /**
     * Publish an article
     */
    public function publish(Request $request, ResearchArticle $researchArticle)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-articles.publish')) {
            abort(403, 'You do not have permission to publish research articles.');
        }

        if (!auth()->user()->canPublish()) {
            abort(403, 'Unauthorized access. Super Admin privileges required.');
        }

        if ($researchArticle->status !== 'approved') {
            return redirect()->back()
                ->with('error', __('messages.error.research_article.publish'));
        }

        $validator = Validator::make($request->all(), [
            'published_at' => 'nullable|date|after_or_equal:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $publishedAt = $request->published_at ? now()->parse($request->published_at) : now();

        $researchArticle->update([
            'status' => 'published',
            'published_by' => auth()->id(),
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.research-articles.index')
            ->with('success', __('messages.success.research_article.published'));
    }

    /**
     * Store a new research category
     */
    public function storeCategory(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.create')) {
            abort(403, 'You do not have permission to create research categories.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:research_categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = ResearchCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.success.research_category.created'),
            'category' => $category->load('parent')
        ]);
    }

    /**
     * Update a research category
     */
    public function updateCategory(Request $request, ResearchCategory $category)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.edit')) {
            abort(403, 'You do not have permission to edit research categories.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:research_categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.success.research_category.updated'),
            'category' => $category->load('parent')
        ]);
    }

    /**
     * Delete a research category
     */
    public function deleteCategory(ResearchCategory $category)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.delete')) {
            abort(403, 'You do not have permission to delete research categories.');
        }
        // Check if category has articles
        if ($category->articles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error.research_category.has_articles')
            ], 422);
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error.research_category.has_children')
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('messages.success.research_category.deleted')
        ]);
    }

    /**
     * Store a new research tag
     */
    public function storeTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:research_tags,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tag = ResearchTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.success.tag.created'),
            'tag' => $tag
        ]);
    }

    /**
     * Update a research tag
     */
    public function updateTag(Request $request, ResearchTag $tag)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:research_tags,name,' . $tag->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.success.tag.updated'),
            'tag' => $tag
        ]);
    }

    /**
     * Delete a research tag
     */
    public function deleteTag(ResearchTag $tag)
    {
        // Detach from all articles
        $tag->articles()->detach();

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => __('messages.success.tag.deleted')
        ]);
    }

    /**
     * Get all categories (for AJAX)
     */
    public function getCategories()
    {
        $categories = ResearchCategory::active()->get();
        return response()->json($categories);
    }

    /**
     * Get all tags (for AJAX)
     */
    public function getTags()
    {
        $tags = ResearchTag::all();
        return response()->json($tags);
    }

    /**
     * Upload image for editor
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'error' => 'Invalid image file'
            ], 422);
        }

        $path = $request->file('image')->store('research-articles/content', 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $url
            ]
        ]);
    }

    /**
     * Upload document/file for editor
     */
    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'error' => 'Invalid file format'
            ], 422);
        }

        $file = $request->file('file');
        $path = $file->store('research-articles/documents', 'public');
        $url = asset('storage/' . $path);
        $name = $file->getClientOriginalName();
        $size = $file->getSize();

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $url,
                'name' => $name,
                'size' => $size
            ]
        ]);
    }
}
