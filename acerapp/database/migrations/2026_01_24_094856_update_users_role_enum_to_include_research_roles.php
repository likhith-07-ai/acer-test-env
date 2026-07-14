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
        // Modify the enum to include new roles
        // Check database driver - SQLite doesn't support MODIFY COLUMN
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, role is already a string column, so no modification needed
            // The original migration handles SQLite differently
            return;
        }
        
        // For MySQL/MariaDB - update enum values
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'public', 'author', 'reviewer', 'super_admin') DEFAULT 'public'");
        } catch (\Exception $e) {
            // If enum already has these values, ignore the error
            if (strpos($e->getMessage(), 'Duplicate') === false && strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check database driver
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        // Revert to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'public') DEFAULT 'public'");
    }
};
