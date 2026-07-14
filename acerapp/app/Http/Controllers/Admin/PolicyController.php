<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePolicyRequest;
use App\Http\Requests\Admin\UpdatePolicyRequest;
use App\Models\Policy;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PoliciesExport;
use ZipArchive;

class PolicyController extends Controller
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
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.view')) {
            abort(403, 'You do not have permission to view policies.');
        }

        $query = Policy::with(['creator', 'updater']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_restricted')) {
            $query->where('is_restricted', $request->is_restricted === '1');
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
                    ->orWhere('tagline', 'like', "%{$search}%");
            });
        }

        $policies = $query->latest()->paginate(config('pagination.admin_per_page'))->appends($request->query());

        return view('admin.policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.create')) {
            abort(403, 'You do not have permission to create policies.');
        }

        return view('admin.policies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePolicyRequest $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.create')) {
            abort(403, 'You do not have permission to create policies.');
        }

        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'tagline' => $request->tagline,
                'status' => $request->status,
                'is_restricted' => $request->has('is_restricted') ? true : false,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];

            // Handle icon (now stored as string/class name)
            if ($request->filled('icon')) {
                $data['icon'] = $request->icon;
            }

            // Handle PDF file upload
            if ($request->hasFile('file')) {
                $data['file_path'] = $request->file('file')->store('policies', 'public');
            }

            $policy = Policy::create($data);

            DB::commit();

            // Log audit
            $this->auditService->logPolicyCreate($policy);

            Log::info('Policy created successfully', [
                'user_id' => auth()->id(),
                'policy_id' => $policy->id,
                'policy_title' => $policy->title,
            ]);

            return redirect()->route('admin.policies.index')
                ->with('success', __('messages.success.policy.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating policy: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', 'file']),
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
    public function show(Policy $policy)
    {
        $policy->load(['creator', 'updater', 'auditLogs.performer']);
        return view('admin.policies.show', compact('policy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Policy $policy)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.edit')) {
            abort(403, 'You do not have permission to edit policies.');
        }

        return view('admin.policies.edit', compact('policy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePolicyRequest $request, Policy $policy)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.edit')) {
            abort(403, 'You do not have permission to edit policies.');
        }

        try {
            DB::beginTransaction();

            $oldData = $policy->toArray();

            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'tagline' => $request->tagline,
                'status' => $request->status,
                'is_restricted' => $request->has('is_restricted') ? true : false,
                'updated_by' => auth()->id(),
            ];

            // Handle icon (now stored as string/class name)
            if ($request->filled('icon')) {
                $data['icon'] = $request->icon;
            } elseif ($request->has('icon') && $request->icon === '') {
                // Allow clearing icon
                $data['icon'] = null;
            }

            // Handle PDF file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($policy->file_path) {
                    Storage::disk('public')->delete($policy->file_path);
                }
                $data['file_path'] = $request->file('file')->store('policies', 'public');
            }

            $policy->update($data);

            DB::commit();

            // Log audit
            $this->auditService->logPolicyUpdate($policy, $oldData);

            Log::info('Policy updated successfully', [
                'user_id' => auth()->id(),
                'policy_id' => $policy->id,
                'policy_title' => $policy->title,
            ]);

            return redirect()->route('admin.policies.index')
                ->with('success', __('messages.success.policy.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating policy: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'policy_id' => $policy->id,
                'request_data' => $request->except(['_token', '_method', 'file']),
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
    public function destroy(Policy $policy)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.delete')) {
            abort(403, 'You do not have permission to delete policies.');
        }

        try {
            DB::beginTransaction();

            // Log audit before deletion
            $this->auditService->logPolicyDelete($policy);

            // Delete PDF file from storage (icon is now a string, not a file)
            if ($policy->file_path) {
                Storage::disk('public')->delete($policy->file_path);
            }

            $policyTitle = $policy->title;
            $policy->delete();

            DB::commit();

            Log::info('Policy deleted successfully', [
                'user_id' => auth()->id(),
                'policy_id' => $policy->id,
                'policy_title' => $policyTitle,
            ]);

            return redirect()->route('admin.policies.index')
                ->with('success', __('messages.success.policy.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting policy: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'policy_id' => $policy->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.delete'));
        }
    }

    /**
     * Download policy PDF file
     */
    public function download(Policy $policy)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.download')) {
            abort(403, 'You do not have permission to download policies.');
        }

        if (!$policy->file_path || !Storage::disk('public')->exists($policy->file_path)) {
            abort(404, 'PDF file not found.');
        }

        // Get file extension
        $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);
        
        // Create safe filename from policy title
        $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
        $safeTitle = preg_replace('/_+/', '_', $safeTitle); // Replace multiple underscores with single
        $safeTitle = trim($safeTitle, '_'); // Remove leading/trailing underscores
        
        // Format: Policy_Title_ID.extension
        $fileName = $safeTitle . '_' . $policy->id . '.' . $extension;
        
        return Storage::disk('public')->download($policy->file_path, $fileName);
    }

    /**
     * Export policies to CSV/Excel
     */
    public function export(Request $request)
    {
        return Excel::download(new PoliciesExport($request), 'policies.xlsx');
    }

    /**
     * Export all policies as ZIP file
     */
    public function exportZip(Request $request)
    {
        // Check permission
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasPermission('policies.export')) {
            abort(403, 'You do not have permission to export policies.');
        }

        try {
            $query = Policy::query();

            // Apply filters (same as index method)
            // Support both single status and multiple status
            if ($request->filled('status')) {
                $statuses = is_array($request->status) ? $request->status : [$request->status];
                $query->whereIn('status', $statuses);
            }

            if ($request->filled('is_restricted')) {
                $query->where('is_restricted', $request->is_restricted === '1');
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
                        ->orWhere('tagline', 'like', "%{$search}%");
                });
            }

            $policies = $query->get();

            if ($policies->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No policies found to export.');
            }

            // Build filename with selected status
            $statuses = $request->filled('status') 
                ? (is_array($request->status) ? $request->status : [$request->status])
                : [];
            
            $statusNames = !empty($statuses) ? implode('_', array_map('ucfirst', $statuses)) : 'all';
            $zipFileName = 'policies_export_' . $statusNames . '_' . date('Y-m-d_His') . '.zip';
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

            // Organize policies by status
            $filesAdded = 0;
            $skippedFiles = 0;

            foreach ($policies as $policy) {
                if (!$policy->file_path || !Storage::disk('public')->exists($policy->file_path)) {
                    $skippedFiles++;
                    continue; // Skip if file doesn't exist
                }

                $filePath = Storage::disk('public')->path($policy->file_path);

                // Verify file actually exists on filesystem
                if (!file_exists($filePath)) {
                    $skippedFiles++;
                    continue;
                }

                $status = $policy->status ?? 'Other';
                $accessType = $policy->is_restricted ? 'Restricted' : 'Public';

                // Build folder structure: Status/AccessType/filename
                $folderPath = $status . '/' . $accessType;

                // Get file extension and create safe filename
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
                $fileName = $safeTitle . '_' . $policy->id . '.' . $extension;

                $zipPathInArchive = $folderPath . '/' . $fileName;

                // Add file to ZIP - read file content and add it
                $fileContent = file_get_contents($filePath);
                if ($fileContent !== false && $zip->addFromString($zipPathInArchive, $fileContent)) {
                    $filesAdded++;
                } else {
                    $skippedFiles++;
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
                    'total_policies' => $policies->count(),
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

            // Log the export
            Log::info('Policies exported as ZIP', [
                'user_id' => auth()->id(),
                'zip_file' => $zipFileName,
                'zip_size' => filesize($zipPath),
                'files_added' => $filesAdded,
                'files_skipped' => $skippedFiles,
                'policy_count' => $policies->count(),
                'filters' => $request->all(),
            ]);

            // Return download response
            return response()->download($zipPath)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error exporting policies as ZIP: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', __('messages.error.general.fetch'));
        }
    }
}
