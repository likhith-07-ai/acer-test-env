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
        // Check database driver - SQLite doesn't support MODIFY COLUMN for enum
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, if column is string type, no modification needed
            return;
        }
        
        // For MySQL/MariaDB - update enum values to include OTHER
        try {
            DB::statement("ALTER TABLE doc_categories MODIFY COLUMN regulatory_body ENUM('SEBI', 'RBI', 'OTHER')");
        } catch (\Exception $e) {
            // If enum already has these values or column doesn't exist, ignore the error
            if (strpos($e->getMessage(), 'Duplicate') === false && 
                strpos($e->getMessage(), 'already exists') === false &&
                strpos($e->getMessage(), "doesn't exist") === false) {
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
        
        // Revert to original enum values (without OTHER)
        try {
            DB::statement("ALTER TABLE doc_categories MODIFY COLUMN regulatory_body ENUM('SEBI', 'RBI')");
        } catch (\Exception $e) {
            // Ignore errors during rollback
        }
    }
};
