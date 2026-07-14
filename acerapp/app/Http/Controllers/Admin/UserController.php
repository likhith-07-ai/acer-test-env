<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only super admin can access users module
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can access users module.');
        }

        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $role = \App\Models\Role::where('name', $request->role)->first();
            if ($role) {
                $query->where('role_id', $role->id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with(['permissions', 'roleModel'])->latest()->paginate(config('pagination.admin_per_page'))->appends($request->query());

        $roles = \App\Models\Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only super admin can create users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can create users.');
        }

        $roles = \App\Models\Role::all();
        // Get role models with permissions (empty collection if no roles exist yet)
        $roleModels = \App\Models\Role::with('permissions')->get()->keyBy('name');
        
        // Prepare role permissions data for JavaScript (keyed by role ID)
        $rolePermissionsData = [];
        foreach ($roleModels as $role) {
            $rolePermissionsData[$role->id] = [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'permissions' => $role->permissions->groupBy('group')->map(function($perms) {
                    return $perms->map(function($p) {
                        return [
                            'id' => $p->id,
                            'display_name' => $p->display_name,
                            'description' => $p->description
                        ];
                    })->values();
                })->toArray()
            ];
        }
        
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $rolePermissionsJson = json_encode($rolePermissionsData);
        return view('admin.users.create', compact('roles', 'permissions', 'rolePermissionsJson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only super admin can create users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can create users.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            // Automatically sync permissions from role
            $user->syncPermissionsFromRole();

            DB::commit();

            Log::info('User created with permissions', [
                'created_by' => auth()->id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role' => $user->role,
                'permissions_count' => $user->permissions()->count(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.success.user.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage(), [
                'created_by' => auth()->id(),
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
    public function show(User $user)
    {
        // Only super admin can view users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can view users.');
        }

        $user->load('permissions');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Only super admin can edit users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can edit users.');
        }

        $roles = \App\Models\Role::all();
        // Get role models with permissions (empty collection if no roles exist yet)
        $roleModels = \App\Models\Role::with('permissions')->get()->keyBy('name');
        
        // Prepare role permissions data for JavaScript (keyed by role ID)
        $rolePermissionsData = [];
        foreach ($roleModels as $role) {
            $rolePermissionsData[$role->id] = [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'permissions' => $role->permissions->groupBy('group')->map(function($perms) {
                    return $perms->map(function($p) {
                        return [
                            'id' => $p->id,
                            'display_name' => $p->display_name,
                            'description' => $p->description
                        ];
                    })->values();
                })->toArray()
            ];
        }
        
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $rolePermissionsJson = json_encode($rolePermissionsData);
        $user->load(['permissions', 'roleModel']);
        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'rolePermissionsJson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only super admin can update users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can update users.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Automatically sync permissions from role
            $user->syncPermissionsFromRole();

            DB::commit();

            Log::info('User updated with permissions', [
                'updated_by' => auth()->id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role' => $user->role,
                'permissions_count' => $user->permissions()->count(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.success.user.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage(), [
                'updated_by' => auth()->id(),
                'user_id' => $user->id,
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
    public function destroy(User $user)
    {
        // Only super admin can delete users
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can delete users.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', __('messages.error.user.delete_self'));
        }

        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return redirect()->back()
                ->with('error', 'Cannot delete super admin user.');
        }

        try {
            DB::beginTransaction();

            $userEmail = $user->email;
            $userId = $user->id;
            $currentAdminId = auth()->id();

            // Handle audit logs - delete logs performed by this user
            // Since performed_by has onDelete('restrict'), we need to delete these first
            AuditLog::where('performed_by', $userId)->delete();

            // Handle documents - update created_by and updated_by to current admin
            // Since these have onDelete('restrict'), we need to update them first
            Document::where('created_by', $userId)->update(['created_by' => $currentAdminId]);
            Document::where('updated_by', $userId)->update(['updated_by' => $currentAdminId]);

            // Detach permissions (belongsToMany relationship)
            $user->permissions()->detach();

            // Now delete the user
            // research_articles.author_id has onDelete('cascade'), so those will be deleted automatically
            $user->delete();

            DB::commit();

            Log::info('User deleted', [
                'deleted_by' => $currentAdminId,
                'user_email' => $userEmail,
                'user_id' => $userId,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.success.user.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage(), [
                'deleted_by' => auth()->id(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', __('messages.error.general.delete'));
        }
    }
}
