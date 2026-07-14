<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('name', 'admin')->first();

        if ($adminRole) {
            User::updateOrCreate(
                ['email' => 'admin@acerratings.com'],
                [
                    'name' => 'Admin User',
                    'email' => 'admin@acerratings.com',
                    'password' => Hash::make('admin@123'),
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        } else {
            $this->command->error('Admin role not found. Please run RoleSeeder first.');
            return;
        }

        $this->command->info('Admin User created successfully!');
        $this->command->info('Email: admin@acerratings.com');
        $this->command->info('Password: admin@123');
    }
}
