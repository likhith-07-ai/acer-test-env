<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('doc_categories', function (Blueprint $table) {
            // Set default value for existing NULL records
            \DB::table('doc_categories')
                ->whereNull('regulatory_body')
                ->update(['regulatory_body' => 'SEBI']);
            
            // Make the column NOT NULL
            $table->enum('regulatory_body', ['SEBI', 'RBI', 'OTHER'])->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_categories', function (Blueprint $table) {
            $table->enum('regulatory_body', ['SEBI', 'RBI', 'OTHER'])->nullable()->change();
        });
    }
};
