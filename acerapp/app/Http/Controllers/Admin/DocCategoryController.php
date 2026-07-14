<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocCategoryRequest;
use App\Http\Requests\Admin\UpdateDocCategoryRequest;
use App\Models\DocCategory;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocCategoryController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.view')) {
            abort(403, 'You do not have permission to view document categories.');
        }

        try {
            $query = DocCategory::whereNull('parent_id')->with('children');

            // Filter by regulatory body
            if ($request->filled('regulatory_body')) {
                $query->where('regulatory_body', $request->regulatory_body);
            }

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhereHas('children', function ($childQuery) use ($search) {
                            $childQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('short_description', 'like', "%{$search}%");
                        });
                });
            }

            // Date filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $categories = $query->latest()
                ->paginate(config('pagination.admin_per_page'))
                ->appends($request->query());

            return view('admin.doc-categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching document categories: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.fetch'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.create')) {
            abort(403, 'You do not have permission to create document categories.');
        }

        try {
            $parentCategories = DocCategory::whereNull('parent_id')->orderBy('name')->get();
            return view('admin.doc-categories.create', compact('parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading document category create form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.doc-categories.index')
                ->with('error', __('messages.error.general.create_form'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocCategoryRequest $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.create')) {
            abort(403, 'You do not have permission to create document categories.');
        }

        try {
            DB::beginTransaction();

            // For sub-categories, inherit regulatory_body from parent if not provided
            $regulatoryBody = $request->regulatory_body;
            if ($request->parent_id && !$regulatoryBody) {
                $parent = DocCategory::find($request->parent_id);
                if ($parent) {
                    $regulatoryBody = $parent->regulatory_body;
                }
            }

            $category = DocCategory::create([
                'name' => $request->name,
                'regulatory_body' => $regulatoryBody,
                'short_description' => $request->short_description,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            // Log audit
            $this->auditService->logCreate($category);

            Log::info('Document category created successfully', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            // Handle AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.success.category.created'),
                    'category' => $category->load('parent')
                ]);
            }

            return redirect()->route('admin.doc-categories.index')
                ->with('success', __('messages.success.category.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating document category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString(),
            ]);

            // Handle AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.general.create')
                ], 500);
            }

            return redirect()->back()
                ->with('error', __('messages.error.general.create'))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocCategory $docCategory)
    {
        try {
            $docCategory->load(['parent', 'children', 'documents', 'subCategoryDocuments']);
            return view('admin.doc-categories.show', compact('docCategory'));
        } catch (\Exception $e) {
            Log::error('Error loading document category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $docCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.doc-categories.index')
                ->with('error', __('messages.error.general.load'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocCategory $docCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.edit')) {
            abort(403, 'You do not have permission to edit document categories.');
        }

        try {
            $parentCategories = DocCategory::whereNull('parent_id')
                ->where('id', '!=', $docCategory->id)
                ->orderBy('name')
                ->get();

            // Load children (sub-categories) if this is a parent category
            $docCategory->load('children');

            return view('admin.doc-categories.edit', compact('docCategory', 'parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading document category edit form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $docCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.doc-categories.index')
                ->with('error', __('messages.error.general.create_form'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocCategoryRequest $request, DocCategory $docCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.edit')) {
            abort(403, 'You do not have permission to edit document categories.');
        }

        try {
            DB::beginTransaction();

            // For sub-categories, inherit regulatory_body from parent if not provided
            $regulatoryBody = $request->regulatory_body;
            if ($request->parent_id && !$regulatoryBody) {
                $parent = DocCategory::find($request->parent_id);
                if ($parent) {
                    $regulatoryBody = $parent->regulatory_body;
                }
            }

            $oldData = $docCategory->toArray();

            $docCategory->update([
                'name' => $request->name,
                'regulatory_body' => $regulatoryBody,
                'short_description' => $request->short_description,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            // Log audit
            $this->auditService->logUpdate($docCategory, $oldData);

            Log::info('Document category updated successfully', [
                'user_id' => auth()->id(),
                'category_id' => $docCategory->id,
                'category_name' => $docCategory->name,
            ]);

            // Handle AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.success.category.updated'),
                    'category' => $docCategory->fresh()->load('parent')
                ]);
            }

            return redirect()->route('admin.doc-categories.index')
                ->with('success', __('messages.success.category.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating document category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $docCategory->id,
                'request_data' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.general.update')
                ], 500);
            }

            return redirect()->back()
                ->with('error', __('messages.error.general.update'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocCategory $docCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.delete')) {
            abort(403, 'You do not have permission to delete document categories.');
        }

        try {
            // Check if category has documents
            if ($docCategory->documents()->count() > 0 || $docCategory->subCategoryDocuments()->count() > 0) {
                Log::warning('Attempt to delete document category with documents', [
                    'user_id' => auth()->id(),
                    'category_id' => $docCategory->id,
                    'documents_count' => $docCategory->documents()->count(),
                    'sub_category_documents_count' => $docCategory->subCategoryDocuments()->count(),
                ]);

                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.error.category.has_documents')
                    ], 422);
                }
                return redirect()->route('admin.doc-categories.index')
                    ->with('error', __('messages.error.category.has_documents'));
            }

            // Check if category has children
            if ($docCategory->children()->count() > 0) {
                Log::warning('Attempt to delete document category with sub-categories', [
                    'user_id' => auth()->id(),
                    'category_id' => $docCategory->id,
                    'children_count' => $docCategory->children()->count(),
                ]);

                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.error.category.has_sub_categories')
                    ], 422);
                }
                return redirect()->route('admin.doc-categories.index')
                    ->with('error', __('messages.error.category.has_sub_categories'));
            }

            $categoryId = $docCategory->id;
            $categoryName = $docCategory->name;

            // Log audit before deletion
            $this->auditService->logDelete($docCategory);

            $docCategory->delete();

            Log::info('Document category deleted successfully', [
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'category_name' => $categoryName,
            ]);

            // Handle AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.success.category.deleted')
                ]);
            }

            return redirect()->route('admin.doc-categories.index')
                ->with('success', __('messages.success.category.deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting document category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $docCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.general.delete')
                ], 500);
            }

            return redirect()->back()
                ->with('error', __('messages.error.general.delete'));
        }
    }
}
