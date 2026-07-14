<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = Category::whereNull('parent_id')
                ->with('children')
                ->latest()
                ->paginate(config('pagination.admin_per_page'))
                ->appends($request->query());
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage(), [
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
        try {
            $parentCategories = Category::whereNull('parent_id')->get();
            return view('admin.categories.create', compact('parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading category create form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.categories.index')
                ->with('error', __('messages.error.general.edit_form'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            Log::info('Category created successfully', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.success.category.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating category: ' . $e->getMessage(), [
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
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        try {
            $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
            return view('admin.categories.edit', compact('category', 'parentCategories'));
        } catch (\Exception $e) {
            Log::error('Error loading category edit form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.categories.index')
                ->with('error', __('messages.error.general.edit_form'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            Log::info('Category updated successfully', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.success.category.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
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
    public function destroy(Category $category)
    {
        try {
            // Check if category has documents
            if ($category->documents()->count() > 0 || $category->subCategoryDocuments()->count() > 0) {
                Log::warning('Attempt to delete category with documents', [
                    'user_id' => auth()->id(),
                    'category_id' => $category->id,
                    'documents_count' => $category->documents()->count(),
                    'sub_category_documents_count' => $category->subCategoryDocuments()->count(),
                ]);
                return redirect()->back()
                    ->with('error', __('messages.error.category.has_documents'));
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                Log::warning('Attempt to delete category with sub-categories', [
                    'user_id' => auth()->id(),
                    'category_id' => $category->id,
                    'children_count' => $category->children()->count(),
                ]);
                return redirect()->back()
                    ->with('error', __('messages.error.category.has_children'));
            }

            $categoryId = $category->id;
            $categoryName = $category->name;
            $category->delete();

            Log::info('Category deleted successfully', [
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'category_name' => $categoryName,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.success.category.deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.delete'));
        }
    }
}
