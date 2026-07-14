<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'role', // Keep for backward compatibility during migration
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get role name (for backward compatibility)
     */
    public function getRoleAttribute(): ?string
    {
        // First check if old role column exists and has value (before migration)
        if (isset($this->attributes['role']) && $this->attributes['role']) {
            return $this->attributes['role'];
        }
        
        // Check if role_id column exists (after migration)
        if (Schema::hasColumn('users', 'role_id')) {
            // Try to get from relationship if loaded
            if ($this->relationLoaded('roleModel') && $this->roleModel) {
                return $this->roleModel->name;
            }
            
            // Try to load and get
            if (isset($this->attributes['role_id']) && $this->attributes['role_id']) {
                try {
                    $this->load('roleModel');
                    if ($this->roleModel) {
                        return $this->roleModel->name;
                    }
                } catch (\Exception $e) {
                    // If relationship fails, try direct query
                    $role = Role::find($this->attributes['role_id']);
                    if ($role) {
                        return $role->name;
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        $roleName = $this->getRoleAttribute();
        return $roleName === 'admin';
    }

    /**
     * Check if user is author
     */
    public function isAuthor(): bool
    {
        $roleName = $this->getRoleAttribute();
        return $roleName === 'author';
    }

    /**
     * Check if user is reviewer
     */
    public function isReviewer(): bool
    {
        $roleName = $this->getRoleAttribute();
        return $roleName === 'reviewer';
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        $roleName = $this->getRoleAttribute();
        return $roleName === 'super_admin';
    }

    /**
     * Check if user can approve articles
     */
    public function canApprove(): bool
    {
        $roleName = $this->roleModel?->name;
        return in_array($roleName, ['admin', 'reviewer', 'super_admin']);
    }

    /**
     * Check if user can publish articles
     */
    public function canPublish(): bool
    {
        $roleName = $this->roleModel?->name;
        return in_array($roleName, ['admin', 'super_admin']);
    }

    /**
     * Check if user can manage research articles
     */
    public function canManageResearchArticles(): bool
    {
        $roleName = $this->roleModel?->name;
        return in_array($roleName, ['admin', 'author', 'reviewer', 'super_admin']);
    }

    /**
     * Research articles created by this user
     */
    public function researchArticles()
    {
        return $this->hasMany(ResearchArticle::class, 'author_id');
    }

    /**
     * Documents created by this user
     */
    public function createdDocuments()
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    /**
     * Documents updated by this user
     */
    public function updatedDocuments()
    {
        return $this->hasMany(Document::class, 'updated_by');
    }

    /**
     * Audit logs performed by this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'performed_by');
    }

    /**
     * Role model relationship
     */
    public function roleModel(): BelongsTo
    {
        // Check if role_id column exists, otherwise use old role column
        if (Schema::hasColumn('users', 'role_id')) {
            return $this->belongsTo(Role::class, 'role_id');
        }
        // Fallback for old structure (before migration)
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Permissions assigned to this user (synced from role)
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get permissions from role
     */
    public function getRolePermissions()
    {
        try {
            // Try to get role model
            $role = null;
            if (Schema::hasColumn('users', 'role_id') && isset($this->attributes['role_id']) && $this->attributes['role_id']) {
                if ($this->relationLoaded('roleModel') && $this->roleModel) {
                    $role = $this->roleModel;
                } else {
                    $role = Role::find($this->attributes['role_id']);
                }
            } elseif (isset($this->attributes['role']) && $this->attributes['role']) {
                $role = Role::where('name', $this->attributes['role'])->first();
            }
            
            if ($role) {
                return $role->permissions;
            }
        } catch (\Exception $e) {
            // If role check fails, return empty collection
        }
        
        return collect([]);
    }

    /**
     * Sync permissions from role
     * NOTE: Permissions are now checked directly from role, not synced to user table
     * This method is kept for compatibility but clears direct permissions
     */
    public function syncPermissionsFromRole(): void
    {
        // Don't sync permissions to user table anymore
        // Permissions are checked directly from role
        // Clear any existing direct permissions to ensure role-only system
        $this->permissions()->detach();
    }

    /**
     * Check if user has a specific permission
     * ONLY checks role permissions - no direct user permissions
     */
    public function hasPermission(string $permissionName): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // ONLY check role permissions - no direct user permissions
        try {
            // Try to get role model
            $role = null;
            if (Schema::hasColumn('users', 'role_id') && isset($this->attributes['role_id']) && $this->attributes['role_id']) {
                if ($this->relationLoaded('roleModel') && $this->roleModel) {
                    $role = $this->roleModel;
                } else {
                    $role = Role::find($this->attributes['role_id']);
                }
            } elseif (isset($this->attributes['role']) && $this->attributes['role']) {
                $role = Role::where('name', $this->attributes['role'])->first();
            }
            
            if ($role && $role->hasPermission($permissionName)) {
                return true;
            }
        } catch (\Exception $e) {
            // Role check failed
        }

        // NO fallback to direct user permissions
        return false;
    }

    /**
     * Check if user has any of the given permissions
     * ONLY checks role permissions - no direct user permissions
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // ONLY check role permissions - no direct user permissions
        try {
            // Try to get role model
            $role = null;
            if (Schema::hasColumn('users', 'role_id') && isset($this->attributes['role_id']) && $this->attributes['role_id']) {
                if ($this->relationLoaded('roleModel') && $this->roleModel) {
                    $role = $this->roleModel;
                } else {
                    $role = Role::find($this->attributes['role_id']);
                }
            } elseif (isset($this->attributes['role']) && $this->attributes['role']) {
                $role = Role::where('name', $this->attributes['role'])->first();
            }
            
            if ($role && $role->permissions()->whereIn('name', $permissionNames)->exists()) {
                return true;
            }
        } catch (\Exception $e) {
            // Role check failed
        }

        // NO fallback to direct user permissions
        return false;
    }

    /**
     * Check if user has all of the given permissions
     * ONLY checks role permissions - no direct user permissions
     */
    public function hasAllPermissions(array $permissionNames): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // ONLY check role permissions - no direct user permissions
        try {
            // Try to get role model
            $role = null;
            if (Schema::hasColumn('users', 'role_id') && isset($this->attributes['role_id']) && $this->attributes['role_id']) {
                if ($this->relationLoaded('roleModel') && $this->roleModel) {
                    $role = $this->roleModel;
                } else {
                    $role = Role::find($this->attributes['role_id']);
                }
            } elseif (isset($this->attributes['role']) && $this->attributes['role']) {
                $role = Role::where('name', $this->attributes['role'])->first();
            }
            
            if ($role) {
                $rolePermissionCount = $role->permissions()->whereIn('name', $permissionNames)->count();
                return $rolePermissionCount === count($permissionNames);
            }
        } catch (\Exception $e) {
            // Role check failed
        }

        // NO fallback to direct user permissions
        return false;
    }

    /**
     * Assign permissions to user (DISABLED - permissions come only from roles)
     * Direct permission assignment is not allowed
     */
    public function assignPermissions(array $permissionIds): void
    {
        // Direct permission assignment is disabled
        // Permissions come only from roles
        // This method does nothing - permissions are managed through roles only
    }

    /**
     * Revoke permissions from user (DISABLED - permissions come only from roles)
     * Direct permission revocation is not allowed
     */
    public function revokePermissions(array $permissionIds): void
    {
        // Direct permission revocation is disabled
        // Permissions come only from roles
        // This method does nothing - permissions are managed through roles only
    }

    /**
     * Boot method to clear direct permissions when role changes
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($user) {
            // When role changes, clear any direct permissions
            // Permissions come only from role, not from direct assignment
            if ($user->wasChanged('role_id') || $user->wasChanged('role')) {
                $user->permissions()->detach();
            }
        });
    }

    /**
     * Check if user can manage users (admin or super_admin)
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->isSuperAdmin();
    }
}
