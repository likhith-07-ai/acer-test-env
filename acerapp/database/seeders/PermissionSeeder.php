<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Documents Permissions
            ['name' => 'documents.view', 'display_name' => 'View Documents', 'group' => 'documents', 'description' => 'View documents list'],
            ['name' => 'documents.create', 'display_name' => 'Create Documents', 'group' => 'documents', 'description' => 'Create new documents'],
            ['name' => 'documents.edit', 'display_name' => 'Edit Documents', 'group' => 'documents', 'description' => 'Edit existing documents'],
            ['name' => 'documents.delete', 'display_name' => 'Delete Documents', 'group' => 'documents', 'description' => 'Delete documents'],
            ['name' => 'documents.export', 'display_name' => 'Export Documents', 'group' => 'documents', 'description' => 'Export documents as ZIP'],
            ['name' => 'documents.download', 'display_name' => 'Download Documents', 'group' => 'documents', 'description' => 'Download document files'],
            ['name' => 'documents.toggle-access', 'display_name' => 'Toggle Access', 'group' => 'documents', 'description' => 'Toggle document access type'],

            // Document Categories Permissions
            ['name' => 'doc-categories.view', 'display_name' => 'View Categories', 'group' => 'documents', 'description' => 'View document categories'],
            ['name' => 'doc-categories.create', 'display_name' => 'Create Categories', 'group' => 'documents', 'description' => 'Create document categories'],
            ['name' => 'doc-categories.edit', 'display_name' => 'Edit Categories', 'group' => 'documents', 'description' => 'Edit document categories'],
            ['name' => 'doc-categories.delete', 'display_name' => 'Delete Categories', 'group' => 'documents', 'description' => 'Delete document categories'],

            // Policies Permissions
            ['name' => 'policies.view', 'display_name' => 'View Policies', 'group' => 'policies', 'description' => 'View policies list'],
            ['name' => 'policies.create', 'display_name' => 'Create Policies', 'group' => 'policies', 'description' => 'Create new policies'],
            ['name' => 'policies.edit', 'display_name' => 'Edit Policies', 'group' => 'policies', 'description' => 'Edit existing policies'],
            ['name' => 'policies.delete', 'display_name' => 'Delete Policies', 'group' => 'policies', 'description' => 'Delete policies'],
            ['name' => 'policies.export', 'display_name' => 'Export Policies', 'group' => 'policies', 'description' => 'Export policies as ZIP'],
            ['name' => 'policies.download', 'display_name' => 'Download Policies', 'group' => 'policies', 'description' => 'Download policy files'],

            // Research Articles Permissions
            ['name' => 'research-articles.view', 'display_name' => 'View Research Articles', 'group' => 'research', 'description' => 'View research articles list'],
            ['name' => 'research-articles.create', 'display_name' => 'Create Research Articles', 'group' => 'research', 'description' => 'Create new research articles'],
            ['name' => 'research-articles.edit', 'display_name' => 'Edit Research Articles', 'group' => 'research', 'description' => 'Edit existing research articles'],
            ['name' => 'research-articles.delete', 'display_name' => 'Delete Research Articles', 'group' => 'research', 'description' => 'Delete research articles'],
            ['name' => 'research-articles.approve', 'display_name' => 'Approve Research Articles', 'group' => 'research', 'description' => 'Approve submitted research articles'],
            ['name' => 'research-articles.publish', 'display_name' => 'Publish Research Articles', 'group' => 'research', 'description' => 'Publish approved research articles'],

            // Research Categories Permissions
            ['name' => 'research-categories.view', 'display_name' => 'View Research Categories', 'group' => 'research', 'description' => 'View research categories'],
            ['name' => 'research-categories.create', 'display_name' => 'Create Research Categories', 'group' => 'research', 'description' => 'Create research categories'],
            ['name' => 'research-categories.edit', 'display_name' => 'Edit Research Categories', 'group' => 'research', 'description' => 'Edit research categories'],
            ['name' => 'research-categories.delete', 'display_name' => 'Delete Research Categories', 'group' => 'research', 'description' => 'Delete research categories'],

            // Research Tags Permissions
            ['name' => 'research-tags.view', 'display_name' => 'View Research Tags', 'group' => 'research', 'description' => 'View research tags'],
            ['name' => 'research-tags.create', 'display_name' => 'Create Research Tags', 'group' => 'research', 'description' => 'Create research tags'],
            ['name' => 'research-tags.edit', 'display_name' => 'Edit Research Tags', 'group' => 'research', 'description' => 'Edit research tags'],
            ['name' => 'research-tags.delete', 'display_name' => 'Delete Research Tags', 'group' => 'research', 'description' => 'Delete research tags'],

            // Press Releases Permissions
            ['name' => 'press-releases.view', 'display_name' => 'View Press Releases', 'group' => 'press-releases', 'description' => 'View press releases list'],
            ['name' => 'press-releases.create', 'display_name' => 'Create Press Releases', 'group' => 'press-releases', 'description' => 'Create new press releases'],
            ['name' => 'press-releases.edit', 'display_name' => 'Edit Press Releases', 'group' => 'press-releases', 'description' => 'Edit existing press releases'],
            ['name' => 'press-releases.delete', 'display_name' => 'Delete Press Releases', 'group' => 'press-releases', 'description' => 'Delete press releases'],
            ['name' => 'press-releases.download', 'display_name' => 'Download Press Releases', 'group' => 'press-releases', 'description' => 'Download press release files'],

            // Users Management Permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'group' => 'users', 'description' => 'View users list'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group' => 'users', 'description' => 'Create new users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'group' => 'users', 'description' => 'Edit existing users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group' => 'users', 'description' => 'Delete users'],
            ['name' => 'users.permissions', 'display_name' => 'Manage User Permissions', 'group' => 'users', 'description' => 'Assign/revoke permissions to users'],

            // Audit Logs Permissions
            ['name' => 'audit-logs.view', 'display_name' => 'View Audit Logs', 'group' => 'system', 'description' => 'View audit logs'],

            // Dashboard Permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'system', 'description' => 'Access admin dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
