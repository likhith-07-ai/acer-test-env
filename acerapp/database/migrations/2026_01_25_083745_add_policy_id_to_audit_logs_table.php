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
        // Check if column exists, if not add it first
        if (!Schema::hasColumn('audit_logs', 'policy_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('policy_id')->nullable()->after('document_id');
            });
        }
        
        // Check if foreign key already exists
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'audit_logs' 
            AND COLUMN_NAME = 'policy_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        // Add foreign key constraint only if it doesn't exist
        if (empty($foreignKeys)) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['policy_id']);
            $table->dropColumn('policy_id');
        });
    }
};
