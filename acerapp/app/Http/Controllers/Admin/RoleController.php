<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only super admin can access roles module
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can access roles module.');
        }

        $query = Role::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%");
            });
        }

        $roles = $query->with('permissions')->latest()->paginate(config('pagination.admin_per_page'))->appends($request->query());

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only super admin can create roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can create roles.');
        }

        // Get all permissions except users group (users module is only accessible by super admin)
        $permissions = Permission::where('group', '!=', 'users')
            ->orderBy('group')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only super admin can create roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can create roles.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            // Assign permissions if provided
            if ($request->filled('permissions')) {
                $role->assignPermissions($request->permissions);
            }

            DB::commit();

            Log::info('Role created with permissions', [
                'created_by' => auth()->id(),
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions_count' => $role->permissions()->count(),
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating role: ' . $e->getMessage(), [
                'created_by' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'Error creating role. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Only super admin can view roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can view roles.');
        }

        $role->load('permissions');
        // Get all permissions except users group (users module is only accessible by super admin)
        $permissions = Permission::where('group', '!=', 'users')
            ->orderBy('group')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group');
        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Only super admin can edit roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can edit roles.');
        }

        // Prevent editing super_admin role
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        // Get all permissions except users group (users module is only accessible by super admin)
        $permissions = Permission::where('group', '!=', 'users')
            ->orderBy('group')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group');
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Only super admin can update roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can update roles.');
        }

        // Prevent editing super_admin role
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            // Sync permissions
            $permissionIds = $request->filled('permissions') ? $request->permissions : [];
            $role->assignPermissions($permissionIds);

            // Sync permissions for all users with this role
            $users = \App\Models\User::where('role_id', $role->id)->get();
            foreach ($users as $user) {
                $user->syncPermissionsFromRole();
            }

            DB::commit();

            Log::info('Role updated with permissions', [
                'updated_by' => auth()->id(),
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions_count' => $role->permissions()->count(),
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating role: ' . $e->getMessage(), [
                'updated_by' => auth()->id(),
                'role_id' => $role->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'Error updating role. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Only super admin can delete roles
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admin can delete roles.');
        }

        // Prevent deleting super_admin role
        if ($role->name === 'super_admin') {
            return redirect()->back()
                ->with('error', 'Super Admin role cannot be deleted.');
        }

        // Check if any users have this role
        $usersCount = \App\Models\User::where('role', $role->name)->count();
        if ($usersCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete role. {$usersCount} user(s) are assigned to this role.");
        }

        try {
            $roleName = $role->name;
            $role->delete();

            Log::info('Role deleted', [
                'deleted_by' => auth()->id(),
                'role_name' => $roleName,
            ]);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage(), [
                'deleted_by' => auth()->id(),
                'role_id' => $role->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('error', 'Error deleting role. Please try again.');
        }
    }
}
