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
            $table->enum('regulatory_body', ['SEBI', 'RBI', 'OTHER'])->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_categories', function (Blueprint $table) {
            $table->dropColumn('regulatory_body');
        });
    }
};
