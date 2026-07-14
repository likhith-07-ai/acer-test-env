# Role and Permission System / भूमिका और अनुमति प्रणाली

## Overview / अवलोकन

Yeh system granular permissions provide karta hai jo users ko specific actions ke liye authorize karta hai. Super Admin ko automatically sabhi permissions milti hain.

## Features / सुविधाएं

### 1. **Super Admin Role**
- **Full Access**: Sabhi permissions automatically
- **User Management**: Users create/edit/delete kar sakta hai
- **Permission Management**: Kisi bhi user ko permissions assign kar sakta hai
- **System Access**: Sabhi modules par full control

### 2. **Permission Groups / अनुमति समूह**

#### Documents Permissions
- `documents.view` - View Documents
- `documents.create` - Create Documents
- `documents.edit` - Edit Documents
- `documents.delete` - Delete Documents
- `documents.export` - Export Documents (ZIP)
- `documents.download` - Download Documents
- `doc-categories.*` - Category management

#### Policies Permissions
- `policies.view` - View Policies
- `policies.create` - Create Policies
- `policies.edit` - Edit Policies
- `policies.delete` - Delete Policies
- `policies.export` - Export Policies (ZIP)
- `policies.download` - Download Policies

#### Research Articles Permissions
- `research-articles.view` - View Research Articles
- `research-articles.create` - Create Research Articles
- `research-articles.edit` - Edit Research Articles
- `research-articles.delete` - Delete Research Articles
- `research-articles.approve` - Approve Articles
- `research-articles.publish` - Publish Articles
- `research-categories.*` - Category management
- `research-tags.*` - Tag management

#### Users Management Permissions
- `users.view` - View Users
- `users.create` - Create Users (Only Super Admin)
- `users.edit` - Edit Users (Only Super Admin)
- `users.delete` - Delete Users (Only Super Admin)
- `users.permissions` - Manage User Permissions (Only Super Admin)

#### System Permissions
- `dashboard.view` - View Dashboard
- `audit-logs.view` - View Audit Logs

## Database Structure / डेटाबेस संरचना

### Tables Created:
1. **permissions** - Stores all available permissions
2. **permission_user** - Pivot table linking users to permissions

### Migration Commands:
```bash
php artisan migrate
php artisan db:seed --class=PermissionSeeder
```

## Usage Examples / उपयोग उदाहरण

### 1. Check Permission in Controller:
```php
// Check single permission
if (!auth()->user()->hasPermission('documents.create')) {
    abort(403, 'You do not have permission to create documents.');
}

// Check multiple permissions (any)
if (!auth()->user()->hasAnyPermission(['documents.create', 'documents.edit'])) {
    abort(403, 'You do not have permission.');
}

// Check multiple permissions (all)
if (!auth()->user()->hasAllPermissions(['documents.create', 'documents.edit'])) {
    abort(403, 'You do not have required permissions.');
}
```

### 2. Use Middleware in Routes:
```php
// Single permission
Route::get('/documents/create', [DocumentController::class, 'create'])
    ->middleware(['auth', 'admin', 'permission:documents.create']);

// Multiple routes with permission
Route::middleware(['auth', 'admin', 'permission:documents.create'])->group(function () {
    Route::get('/documents/create', [DocumentController::class, 'create']);
    Route::post('/documents', [DocumentController::class, 'store']);
});
```

### 3. Check in Blade Views:
```blade
@if(auth()->user()->hasPermission('documents.create'))
    <a href="{{ route('admin.documents.create') }}">Create Document</a>
@endif

@if(auth()->user()->hasAnyPermission(['documents.edit', 'documents.delete']))
    <!-- Show edit/delete buttons -->
@endif
```

### 4. Assign Permissions Programmatically:
```php
// Assign permissions to user
$user->assignPermissions([1, 2, 3]); // Permission IDs

// Revoke permissions
$user->revokePermissions([1, 2]);

// Sync permissions (replace all)
$user->permissions()->sync([1, 2, 3, 4]);
```

## User Management / उपयोगकर्ता प्रबंधन

### Creating Users with Permissions:
1. Go to `/admin/users/create`
2. Fill user details (name, email, password, role)
3. Select permissions from grouped checkboxes
4. Super Admin role automatically gets all permissions

### Editing User Permissions:
1. Go to `/admin/users/{id}/edit`
2. Change role or permissions
3. Permissions are automatically synced

### Viewing User Permissions:
- User index page shows permission count
- User detail page shows all assigned permissions grouped by category

## Super Admin Features / सुपर एडमिन सुविधाएं

### What Super Admin Can Do:
1. ✅ **Create/Edit/Delete Users** - Full user management
2. ✅ **Assign Permissions** - Kisi bhi user ko permissions assign kar sakta hai
3. ✅ **All Module Access** - Documents, Policies, Research Articles sab par access
4. ✅ **System Settings** - Audit logs, dashboard access
5. ✅ **No Restrictions** - Koi bhi permission check bypass hota hai

### Super Admin Restrictions:
- ❌ Cannot delete themselves
- ❌ Cannot delete other super admins
- ⚠️ Should be careful with permissions

## Permission Checking Methods / अनुमति जांच विधियां

### User Model Methods:
```php
// Check single permission
$user->hasPermission('documents.create'); // Returns bool

// Check if user has any of the permissions
$user->hasAnyPermission(['documents.create', 'policies.create']); // Returns bool

// Check if user has all permissions
$user->hasAllPermissions(['documents.create', 'documents.edit']); // Returns bool

// Assign permissions
$user->assignPermissions([1, 2, 3]); // Array of permission IDs

// Revoke permissions
$user->revokePermissions([1, 2]); // Array of permission IDs
```

## Implementation Checklist / कार्यान्वयन चेकलिस्ट

### ✅ Completed:
- [x] Permissions table migration
- [x] Permission model
- [x] User-Permission relationship
- [x] Permission seeder with all permissions
- [x] UserController updated for permissions
- [x] User create/edit views with permissions
- [x] Permission checking methods in User model
- [x] CheckPermission middleware
- [x] Users index/show views updated

### 📋 Next Steps (Optional):
- [ ] Apply permission checks to existing controllers
- [ ] Add permission checks to routes
- [ ] Update views to show/hide based on permissions
- [ ] Create permission management UI (if needed)
- [ ] Add permission-based filtering in listings

## Testing / परीक्षण

### Test Scenarios:
1. **Super Admin**: Should have access to everything
2. **Admin with Permissions**: Should only access assigned permissions
3. **User Creation**: Permissions should be assigned correctly
4. **Permission Updates**: Changes should reflect immediately
5. **Middleware**: Should block unauthorized access

### Test Commands:
```bash
# Run migrations
php artisan migrate

# Seed permissions
php artisan db:seed --class=PermissionSeeder

# Create test user and assign permissions
php artisan tinker
>>> $user = User::find(1);
>>> $user->assignPermissions([1, 2, 3]);
>>> $user->hasPermission('documents.create');
```

## Security Notes / सुरक्षा नोट्स

1. **Super Admin**: Always has all permissions - use carefully
2. **Permission Checks**: Always verify in both middleware and controllers
3. **UI Hiding**: Hiding buttons is not security - always check server-side
4. **Audit Logs**: All permission changes are logged
5. **User Deletion**: Super admins cannot be deleted

## File Structure / फाइल संरचना

```
app/
├── Models/
│   ├── User.php (updated with permissions)
│   └── Permission.php (new)
├── Http/
│   ├── Controllers/Admin/
│   │   └── UserController.php (updated)
│   └── Middleware/
│       └── CheckPermission.php (new)
database/
├── migrations/
│   ├── create_permissions_table.php (new)
│   └── create_permission_user_table.php (new)
└── seeders/
    └── PermissionSeeder.php (new)
resources/views/admin/users/
├── create.blade.php (updated)
├── edit.blade.php (updated)
├── index.blade.php (updated)
└── show.blade.php (updated)
```

## Summary / सारांश

Yeh system aapko granular control deta hai ki kaun user kya kar sakta hai. Super Admin ko automatically sabhi permissions milti hain, aur baaki users ko specific permissions assign ki ja sakti hain.

**Key Points:**
- Super Admin = Full Access (automatic)
- Other Users = Selected Permissions Only
- Permissions grouped by module (documents, policies, research, etc.)
- Easy to assign/edit permissions via UI
- Middleware available for route protection

