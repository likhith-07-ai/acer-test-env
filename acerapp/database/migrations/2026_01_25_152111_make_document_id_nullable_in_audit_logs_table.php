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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
            $table->dropForeign(['document_id']);
        });

        // Modify column to be nullable
        DB::statement('ALTER TABLE audit_logs MODIFY document_id BIGINT UNSIGNED NULL');

        Schema::table('audit_logs', function (Blueprint $table) {
            // Re-add foreign key constraint
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['document_id']);
        });

        // Modify column to be NOT NULL
        DB::statement('ALTER TABLE audit_logs MODIFY document_id BIGINT UNSIGNED NOT NULL');

        Schema::table('audit_logs', function (Blueprint $table) {
            // Re-add foreign key constraint
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }
};
