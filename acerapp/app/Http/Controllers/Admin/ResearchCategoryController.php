<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResearchCategoryRequest;
use App\Http\Requests\Admin\UpdateResearchCategoryRequest;
use App\Models\ResearchCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResearchCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = ResearchCategory::whereNull('parent_id')
                ->with('children')
                ->latest()
                ->paginate(config('pagination.admin_per_page'))
                ->appends($request->query());
            
            return view('admin.research-categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching research categories: ' . $e->getMessage(), [
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
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.create')) {
            abort(403, 'You do not have permission to create research categories.');
        }

        try {
            $parentCategories = ResearchCategory::whereNull('parent_id')->orderBy('name')->get();
            return view('admin.research-categories.create', compact('parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading research category create form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.research-categories.index')
                ->with('error', __('messages.error.general.create_form'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResearchCategoryRequest $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.create')) {
            abort(403, 'You do not have permission to create research categories.');
        }

        try {
            DB::beginTransaction();

            $category = ResearchCategory::create([
                'name' => $request->name,
                'slug' => ResearchCategory::generateUniqueSlug($request->name),
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            Log::info('Research category created successfully', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            return redirect()->route('admin.research-categories.index')
                ->with('success', __('messages.success.research_category.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating research category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.create'))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchCategory $researchCategory)
    {
        try {
            $researchCategory->load(['parent', 'children', 'articles']);
            return view('admin.research-categories.show', compact('researchCategory'));
        } catch (\Exception $e) {
            Log::error('Error loading research category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $researchCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.research-categories.index')
                ->with('error', __('messages.error.general.load'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResearchCategory $researchCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.edit')) {
            abort(403, 'You do not have permission to edit research categories.');
        }

        try {
            $parentCategories = ResearchCategory::whereNull('parent_id')
                ->where('id', '!=', $researchCategory->id)
                ->orderBy('name')
                ->get();
            
            return view('admin.research-categories.edit', compact('researchCategory', 'parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading research category edit form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $researchCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.research-categories.index')
                ->with('error', __('messages.error.general.create_form'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResearchCategoryRequest $request, ResearchCategory $researchCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.edit')) {
            abort(403, 'You do not have permission to edit research categories.');
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ];

            // Update slug if name changed
            if ($researchCategory->name !== $request->name) {
                $data['slug'] = ResearchCategory::generateUniqueSlug($request->name, $researchCategory->id);
            }

            $researchCategory->update($data);

            DB::commit();

            Log::info('Research category updated successfully', [
                'user_id' => auth()->id(),
                'category_id' => $researchCategory->id,
                'category_name' => $researchCategory->name,
            ]);

            return redirect()->route('admin.research-categories.index')
                ->with('success', __('messages.success.research_category.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating research category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $researchCategory->id,
                'request_data' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.update'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchCategory $researchCategory)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('research-categories.delete')) {
            abort(403, 'You do not have permission to delete research categories.');
        }

        try {
            // Check if category has articles
            if ($researchCategory->articles()->count() > 0) {
                Log::warning('Attempt to delete research category with articles', [
                    'user_id' => auth()->id(),
                    'category_id' => $researchCategory->id,
                    'articles_count' => $researchCategory->articles()->count(),
                ]);
                return redirect()->route('admin.research-categories.index')
                    ->with('error', __('messages.error.research_category.has_articles'));
            }

            // Check if category has children
            if ($researchCategory->children()->count() > 0) {
                Log::warning('Attempt to delete research category with sub-categories', [
                    'user_id' => auth()->id(),
                    'category_id' => $researchCategory->id,
                    'children_count' => $researchCategory->children()->count(),
                ]);
                return redirect()->route('admin.research-categories.index')
                    ->with('error', __('messages.error.research_category.has_children'));
            }

            $categoryId = $researchCategory->id;
            $categoryName = $researchCategory->name;
            $researchCategory->delete();

            Log::info('Research category deleted successfully', [
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'category_name' => $categoryName,
            ]);

            return redirect()->route('admin.research-categories.index')
                ->with('success', __('messages.success.research_category.deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting research category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $researchCategory->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.delete'));
        }
    }
}
