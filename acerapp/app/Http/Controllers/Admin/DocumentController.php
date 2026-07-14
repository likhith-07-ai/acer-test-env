<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Http\Requests\Admin\StoreDocCategoryRequest;
use App\Http\Requests\Admin\UpdateDocCategoryRequest;
use App\Models\DocCategory;
use App\Models\Document;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentsExport;
use ZipArchive;

class DocumentController extends Controller
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
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.view')) {
            abort(403, 'You do not have permission to view documents.');
        }

        $query = Document::with(['category', 'subCategory', 'creator', 'updater']);

        // Apply filters
        if ($request->filled('regulator')) {
            $query->where('regulator', $request->regulator);
        }

        if ($request->filled('access_type')) {
            $query->where('access_type', $request->access_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(config('pagination.admin_per_page'))->appends($request->query());

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.create')) {
            abort(403, 'You do not have permission to create documents.');
        }

        $categories = DocCategory::whereNull('parent_id')->with('children')->get();
        return view('admin.documents.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.create')) {
            abort(403, 'You do not have permission to create documents.');
        }

        try {
            DB::beginTransaction();

            // Handle file upload
            $filePath = $request->file('file')->store('documents', 'public');

            $document = Document::create([
                'regulator' => $request->regulator,
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'access_type' => $request->access_type,
                'file_path' => $filePath,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            // Log audit
            $this->auditService->logCreate($document);

            Log::info('Document created successfully', [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'document_title' => $document->title,
                'category_id' => $document->category_id,
                'sub_category_id' => $document->sub_category_id,
            ]);

            return redirect()->route('admin.documents.index')
                ->with('success', __('messages.success.document.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating document: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', 'file']),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.document.upload'))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.view')) {
            abort(403, 'You do not have permission to view documents.');
        }

        $document->load(['category', 'subCategory', 'creator', 'updater', 'auditLogs.performer']);
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.edit')) {
            abort(403, 'You do not have permission to edit documents.');
        }

        $categories = DocCategory::whereNull('parent_id')->with('children')->get();
        return view('admin.documents.edit', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.edit')) {
            abort(403, 'You do not have permission to edit documents.');
        }

        try {
            DB::beginTransaction();

            $oldData = $document->toArray();

            $data = [
                'regulator' => $request->regulator,
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'access_type' => $request->access_type,
                'updated_by' => auth()->id(),
            ];

            // Handle file upload if new file is provided
            if ($request->hasFile('file')) {
                // Delete old file
                Storage::disk('public')->delete($document->file_path);
                $data['file_path'] = $request->file('file')->store('documents', 'public');
            }

            $document->update($data);

            DB::commit();

            // Log audit
            $this->auditService->logUpdate($document, $oldData);

            Log::info('Document updated successfully', [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'document_title' => $document->title,
                'category_id' => $document->category_id,
                'sub_category_id' => $document->sub_category_id,
            ]);

            return redirect()->route('admin.documents.index')
                ->with('success', __('messages.success.document.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating document: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'request_data' => $request->except(['_token', '_method', 'file']),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.document.update'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.delete')) {
            abort(403, 'You do not have permission to delete documents.');
        }

        try {
            DB::beginTransaction();

            // Log audit before deletion
            $this->auditService->logDelete($document);

            // Delete file from storage
            Storage::disk('public')->delete($document->file_path);

            $documentTitle = $document->title;
            $document->delete();

            DB::commit();

            Log::info('Document deleted successfully', [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'document_title' => $documentTitle,
            ]);

            return redirect()->route('admin.documents.index')
                ->with('success', __('messages.success.document.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting document: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.document.delete'));
        }
    }

    /**
     * Toggle document access type (public/restricted)
     */
    public function toggleAccess(Request $request, Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.toggle-access')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to toggle document access.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $oldAccessType = $document->access_type;
            $newAccessType = $document->access_type === 'public' ? 'restricted' : 'public';

            $document->update([
                'access_type' => $newAccessType,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            // Log audit
            $this->auditService->logUpdate($document, ['access_type' => $oldAccessType]);

            Log::info('Document access type toggled', [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'document_title' => $document->title,
                'old_access_type' => $oldAccessType,
                'new_access_type' => $newAccessType,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.success.document.updated'),
                    'access_type' => $newAccessType,
                    'is_restricted' => $newAccessType === 'restricted',
                ]);
            }

            return redirect()->route('admin.documents.index')
                ->with('success', __('messages.success.document.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling document access: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'document_id' => $document->id,
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.document.update')
                ], 500);
            }

            return redirect()->back()
                ->with('error', __('messages.error.document.update'));
        }
    }

    /**
     * Download document file
     */
    public function download(Document $document)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.download')) {
            abort(403, 'You do not have permission to download documents.');
        }

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'PDF file not found.');
        }

        // Get file extension
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);

        // Create safe filename from document title
        $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $document->title);
        $safeTitle = preg_replace('/_+/', '_', $safeTitle); // Replace multiple underscores with single
        $safeTitle = trim($safeTitle, '_'); // Remove leading/trailing underscores

        // Format: Document_Title_ID.extension
        $fileName = $safeTitle . '_' . $document->id . '.' . $extension;

        return Storage::disk('public')->download($document->file_path, $fileName);
    }

    /**
     * Export documents to CSV/Excel
     */
    public function export(Request $request)
    {
        return Excel::download(new DocumentsExport($request), 'documents.xlsx');
    }

    /**
     * Export all documents as ZIP file
     */
    public function exportZip(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('documents.export')) {
            abort(403, 'You do not have permission to export documents.');
        }

        try {
            $query = Document::with(['category', 'subCategory']);

            // Apply filters (same as index method)
            // Support both single regulator and multiple regulators
            if ($request->filled('regulators')) {
                $regulators = is_array($request->regulators) ? $request->regulators : [$request->regulators];
                $query->whereIn('regulator', $regulators);
            } elseif ($request->filled('regulator')) {
                // Backward compatibility: support single regulator parameter
                $query->where('regulator', $request->regulator);
            }

            if ($request->filled('access_type')) {
                $query->where('access_type', $request->access_type);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $documents = $query->get();

            if ($documents->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No documents found to export.');
            }

            // Debug: Log regulators and document count
            $regulators = $request->filled('regulators') 
                ? (is_array($request->regulators) ? $request->regulators : [$request->regulators])
                : ($request->filled('regulator') ? [$request->regulator] : []);
            
            Log::info('Export ZIP request', [
                'user_id' => auth()->id(),
                'regulators' => $regulators,
                'regulator' => $request->regulator,
                'total_documents' => $documents->count(),
                'all_params' => $request->all(),
            ]);

            // Create temporary ZIP file
            $regulatorNames = !empty($regulators) ? implode('_', $regulators) : 'all';
            $zipFileName = 'documents_export_' . $regulatorNames . '_' . date('Y-m-d_His') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Create temp directory if it doesn't exist
            if (!File::exists(storage_path('app/temp'))) {
                File::makeDirectory(storage_path('app/temp'), 0755, true);
            }

            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                return redirect()->back()
                    ->with('error', 'Failed to create ZIP file.');
            }

            // Organize documents by regulator and category
            $filesAdded = 0;
            $skippedFiles = 0;

            foreach ($documents as $document) {
                if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
                    $skippedFiles++;
                    Log::debug('Skipping document - file not found', [
                        'document_id' => $document->id,
                        'file_path' => $document->file_path,
                        'exists' => $document->file_path ? Storage::disk('public')->exists($document->file_path) : false,
                    ]);
                    continue; // Skip if file doesn't exist
                }

                $filePath = Storage::disk('public')->path($document->file_path);

                // Verify file actually exists on filesystem
                if (!file_exists($filePath)) {
                    $skippedFiles++;
                    Log::debug('Skipping document - file path does not exist on filesystem', [
                        'document_id' => $document->id,
                        'file_path' => $filePath,
                    ]);
                    continue;
                }

                $regulator = $document->regulator ?? 'Other';
                $categoryName = $document->category ? $document->category->name : 'Uncategorized';
                $subCategoryName = $document->subCategory ? $document->subCategory->name : null;

                // Build folder structure: SEBI/Category/SubCategory/filename
                $folderPath = $regulator . '/' . $categoryName;
                if ($subCategoryName) {
                    $folderPath .= '/' . $subCategoryName;
                }

                // Get file extension and create safe filename
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $document->title);
                $fileName = $safeTitle . '_' . $document->id . '.' . $extension;

                $zipPathInArchive = $folderPath . '/' . $fileName;

                // Add file to ZIP - read file content and add it
                $fileContent = file_get_contents($filePath);
                if ($fileContent !== false && $zip->addFromString($zipPathInArchive, $fileContent)) {
                    $filesAdded++;
                } else {
                    $skippedFiles++;
                    Log::warning('Failed to add file to ZIP', [
                        'document_id' => $document->id,
                        'file_path' => $filePath,
                        'zip_path' => $zipPathInArchive,
                        'file_size' => file_exists($filePath) ? filesize($filePath) : 0,
                    ]);
                }
            }

            $zip->close();

            // Check if any files were added
            if ($filesAdded === 0) {
                // Delete empty ZIP file if it exists
                if (file_exists($zipPath)) {
                    unlink($zipPath);
                }

                Log::warning('No files added to ZIP export', [
                    'user_id' => auth()->id(),
                    'total_documents' => $documents->count(),
                    'files_skipped' => $skippedFiles,
                    'filters' => $request->all(),
                ]);

                return redirect()->back()
                    ->with('error', 'No file found for download.');
            }

            // Verify ZIP file exists and has content
            if (!file_exists($zipPath) || filesize($zipPath) === 0) {
                Log::error('ZIP file is empty or does not exist', [
                    'user_id' => auth()->id(),
                    'zip_path' => $zipPath,
                    'exists' => file_exists($zipPath),
                    'size' => file_exists($zipPath) ? filesize($zipPath) : 0,
                ]);

                return redirect()->back()
                    ->with('error', 'Failed to create ZIP file. Please try again.');
            }

            // Log the export with detailed information
            Log::info('Documents exported as ZIP', [
                'user_id' => auth()->id(),
                'zip_file' => $zipFileName,
                'zip_size' => filesize($zipPath),
                'files_added' => $filesAdded,
                'files_skipped' => $skippedFiles,
                'total_documents' => $documents->count(),
                'filters' => $request->all(),
            ]);

            // Return download response
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting documents as ZIP: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', __('messages.error.general.fetch'));
        }
    }

    /**
     * Store a new document category
     */
    public function storeCategory(StoreDocCategoryRequest $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('doc-categories.create')) {
            abort(403, 'You do not have permission to create document categories.');
        }

        try {
            DB::beginTransaction();

            // Validation is handled by StoreDocCategoryRequest
            // It will check for duplicates automatically

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

            Log::info('Document category created via inline form', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('messages.success.category.created'),
                'category' => $category->load('parent')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError ?? 'Validation failed',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating document category via inline form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token']),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => __('messages.error.general.create')
            ], 500);
        }
    }

    /**
     * Update a document category
     */
    public function updateCategory(UpdateDocCategoryRequest $request, DocCategory $category)
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

            $category->update([
                'name' => $request->name,
                'regulatory_body' => $regulatoryBody,
                'short_description' => $request->short_description,
                'parent_id' => $request->parent_id,
            ]);

            DB::commit();

            Log::info('Document category updated via inline form', [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'category_name' => $category->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('messages.success.category.updated'),
                'category' => $category->load('parent')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating document category via inline form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'request_data' => $request->except(['_token', '_method']),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => __('messages.error.general.update')
            ], 500);
        }
    }

    /**
     * Delete a document category
     */
    public function deleteCategory(DocCategory $category)
    {
        try {
            // Check if category has documents
            if ($category->documents()->count() > 0 || $category->subCategoryDocuments()->count() > 0) {
                Log::warning('Attempt to delete document category with documents via inline form', [
                    'user_id' => auth()->id(),
                    'category_id' => $category->id,
                    'documents_count' => $category->documents()->count(),
                    'sub_category_documents_count' => $category->subCategoryDocuments()->count(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.category.has_documents')
                ], 422);
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                Log::warning('Attempt to delete document category with sub-categories via inline form', [
                    'user_id' => auth()->id(),
                    'category_id' => $category->id,
                    'children_count' => $category->children()->count(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => __('messages.error.category.has_sub_categories')
                ], 422);
            }

            $categoryId = $category->id;
            $categoryName = $category->name;
            $category->delete();

            Log::info('Document category deleted via inline form', [
                'user_id' => auth()->id(),
                'category_id' => $categoryId,
                'category_name' => $categoryName,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('messages.success.category.deleted')
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting document category via inline form: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => __('messages.error.general.delete')
            ], 500);
        }
    }

    /**
     * Get all categories (for AJAX)
     */
    public function getCategories()
    {
        $categories = DocCategory::whereNull('parent_id')->with('children')->get();
        return response()->json($categories);
    }
}
