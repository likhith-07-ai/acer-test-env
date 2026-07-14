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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->enum('regulator', ['SEBI', 'RBI', 'OTHER']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('doc_categories')->onDelete('restrict');
            $table->foreignId('sub_category_id')->nullable()->constrained('doc_categories')->onDelete('restrict');
            $table->enum('access_type', ['public', 'restricted'])->default('public');
            $table->string('file_path');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
