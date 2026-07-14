<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure roles table exists and has data
        if (!Schema::hasTable('roles')) {
            // If roles table doesn't exist, create it first
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Add role_id column
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->after('email');
            });
        }

        // Migrate existing role data to role_id
        // Map string roles to role IDs
        $roleMapping = ['admin', 'author', 'reviewer', 'super_admin', 'public'];

        foreach ($roleMapping as $roleName) {
            $role = DB::table('roles')->where('name', $roleName)->first();
            if ($role) {
                DB::table('users')
                    ->where('role', $roleName)
                    ->update(['role_id' => $role->id]);
            }
        }

        // Handle users with NULL role_id - assign default 'public' role
        // This handles users with NULL role or unmapped role values
        $defaultRole = DB::table('roles')->where('name', 'public')->first();
        if ($defaultRole) {
            DB::table('users')
                ->whereNull('role_id')
                ->update(['role_id' => $defaultRole->id]);
        }

        // Make role_id required and add foreign key
        if (!Schema::hasColumn('users', 'role_id')) {
        Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable(false)->change();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            });
        }

        // Drop the old role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back role column
        Schema::table('users', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->string('role')->default('public')->after('email');
            } else {
                $table->enum('role', ['admin', 'public', 'author', 'reviewer', 'super_admin'])->default('public')->after('email');
            }
        });

        // Migrate role_id back to role string
        $roles = DB::table('roles')->get()->keyBy('id');
        $users = DB::table('users')->whereNotNull('role_id')->get();
        
        foreach ($users as $user) {
            if (isset($roles[$user->role_id])) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['role' => $roles[$user->role_id]->name]);
            }
        }

        // Drop foreign key and role_id column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
