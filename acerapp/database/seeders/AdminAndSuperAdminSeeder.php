<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
        $adminRole = \App\Models\Role::where('name', 'admin')->first();

        if (!$superAdminRole || !$adminRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@acer.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@acer.com',
                'password' => Hash::make('superadmin@123'),
                'role_id' => $superAdminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@acer.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@acer.com',
                'password' => Hash::make('admin@123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('========================================');
        $this->command->info('Admin and Super Admin created successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Super Admin Credentials:');
        $this->command->info('  Email: superadmin@acer.com');
        $this->command->info('  Password: superadmin@123');
        $this->command->info('');
        $this->command->info('Admin Credentials:');
        $this->command->info('  Email: admin@acer.com');
        $this->command->info('  Password: admin@123');
        $this->command->info('');
        $this->command->info('========================================');
    }
}
