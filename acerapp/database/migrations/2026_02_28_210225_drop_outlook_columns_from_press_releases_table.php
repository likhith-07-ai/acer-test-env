<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('press_releases', function (Blueprint $table) {
            $table->dropColumn(['outlook', 'outlook_body']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('press_releases', function (Blueprint $table) {
            $table->string('outlook')->nullable();
            $table->text('outlook_body')->nullable();
        });
    }
};