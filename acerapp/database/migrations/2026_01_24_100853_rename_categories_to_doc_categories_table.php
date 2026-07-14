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
        // Rename categories table to doc_categories
        if (Schema::hasTable('categories') && !Schema::hasTable('doc_categories')) {
            Schema::rename('categories', 'doc_categories');
        }
        
        // Update foreign key constraints in documents table
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) {
                // Drop old foreign keys
                $table->dropForeign(['category_id']);
                $table->dropForeign(['sub_category_id']);
            });
            
            Schema::table('documents', function (Blueprint $table) {
                // Add new foreign keys
                $table->foreign('category_id')->references('id')->on('doc_categories')->onDelete('restrict');
                $table->foreign('sub_category_id')->references('id')->on('doc_categories')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert foreign keys
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropForeign(['sub_category_id']);
            });
            
            Schema::table('documents', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
                $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('restrict');
            });
        }
        
        // Rename back to categories
        if (Schema::hasTable('doc_categories') && !Schema::hasTable('categories')) {
            Schema::rename('doc_categories', 'categories');
        }
    }
};
