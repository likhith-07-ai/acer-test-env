<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();

        if ($superAdminRole) {
            User::updateOrCreate(
                ['email' => 'superadmin@acerratings.com'],
                [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@acerratings.com',
                    'password' => Hash::make('superadmin@123'),
                    'role_id' => $superAdminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        } else {
            $this->command->error('Super admin role not found. Please run RoleSeeder first.');
            return;
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@acerratings.com');
        $this->command->info('Password: superadmin@123');
    }
}
