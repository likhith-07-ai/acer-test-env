<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all permissions
        $allPermissions = Permission::all();
        $permissionMap = $allPermissions->keyBy('name');

        // Super Admin Role (no permissions needed - has all automatically)
        Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Has all permissions automatically. Full system access.',
            ]
        );

        // Admin Role - Full access except user management
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full access to documents, policies, and research articles. Can manage categories and tags.',
            ]
        );
        $adminPermissions = $allPermissions->filter(function($perm) {
            return !str_starts_with($perm->name, 'users.');
        })->pluck('id')->toArray();
        $adminRole->assignPermissions($adminPermissions);

        // Author Role - Can create and manage own content
        $authorRole = Role::updateOrCreate(
            ['name' => 'author'],
            [
                'display_name' => 'Author',
                'description' => 'Can create and manage own documents, policies, and research articles.',
            ]
        );
        $authorPermissions = $allPermissions->filter(function($perm) {
            return in_array($perm->name, [
                'documents.view',
                'documents.create',
                'documents.edit',
                'documents.download',
                'policies.view',
                'policies.create',
                'policies.edit',
                'policies.download',
                'research-articles.view',
                'research-articles.create',
                'research-articles.edit',
                'research-categories.view',
                'research-tags.view',
                'dashboard.view',
            ]);
        })->pluck('id')->toArray();
        $authorRole->assignPermissions($authorPermissions);

        // Reviewer Role - Can review and approve content
        $reviewerRole = Role::updateOrCreate(
            ['name' => 'reviewer'],
            [
                'display_name' => 'Reviewer',
                'description' => 'Can view, review, and approve research articles. Can view documents and policies.',
            ]
        );
        $reviewerPermissions = $allPermissions->filter(function($perm) {
            return in_array($perm->name, [
                'documents.view',
                'documents.download',
                'policies.view',
                'policies.download',
                'research-articles.view',
                'research-articles.approve',
                'research-categories.view',
                'research-tags.view',
                'dashboard.view',
            ]);
        })->pluck('id')->toArray();
        $reviewerRole->assignPermissions($reviewerPermissions);

        // Public Role - Read-only access
        $publicRole = Role::updateOrCreate(
            ['name' => 'public'],
            [
                'display_name' => 'Public User',
                'description' => 'Read-only access to public documents and policies.',
            ]
        );
        $publicPermissions = $allPermissions->filter(function($perm) {
            return in_array($perm->name, [
                'documents.view',
                'documents.download',
                'policies.view',
                'policies.download',
                'research-articles.view',
            ]);
        })->pluck('id')->toArray();
        $publicRole->assignPermissions($publicPermissions);
    }
}
