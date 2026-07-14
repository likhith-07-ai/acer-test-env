<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $isSuperAdmin = $user->isSuperAdmin();
            
            $query = AuditLog::with(['performer', 'auditable'])->latest('performed_at');

            // If user is not super admin, only show their own logs
            if (!$isSuperAdmin) {
                $query->where('performed_by', $user->id);
            }

            // Filter by model type
            if ($request->filled('model_type')) {
                $query->forModel($request->model_type);
            }

            // Filter by action
            if ($request->filled('action')) {
                $query->action($request->action);
            }

            // Filter by user (only for super admin)
            if ($request->filled('user_id') && $isSuperAdmin) {
                $query->byUser($request->user_id);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('performed_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('performed_at', '<=', $request->date_to);
            }

            // Search in description
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('model_name', 'like', "%{$search}%");
                });
            }

            $logs = $query->paginate(config('pagination.admin_per_page'))
                ->appends($request->query());

            // Get unique model types for filter (only from logs user can see)
            $modelTypesQuery = AuditLog::distinct('model_name')
                ->whereNotNull('model_name');
            
            if (!$isSuperAdmin) {
                $modelTypesQuery->where('performed_by', $user->id);
            }
            
            $modelTypes = $modelTypesQuery->pluck('model_name')
                ->sort()
                ->values();

            // Get users for filter (only super admin can see all users)
            if ($isSuperAdmin) {
                $users = User::whereHas('auditLogs')->get();
            } else {
                // For regular users, only show themselves
                $users = collect([$user]);
            }

            return view('admin.audit-logs.index', compact('logs', 'modelTypes', 'users', 'isSuperAdmin'));
        } catch (\Exception $e) {
            Log::error('Error fetching audit logs: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.fetch'));
        }
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        try {
            $user = auth()->user();
            
            // If user is not super admin, only allow viewing their own logs
            if (!$user->isSuperAdmin() && $auditLog->performed_by !== $user->id) {
                abort(403, 'You do not have permission to view this audit log.');
            }
            
            $auditLog->load(['performer', 'auditable']);
            return view('admin.audit-logs.show', compact('auditLog'));
        } catch (\Exception $e) {
            Log::error('Error loading audit log: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'audit_log_id' => $auditLog->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.audit-logs.index')
                ->with('error', __('messages.error.general.load'));
        }
    }
}
