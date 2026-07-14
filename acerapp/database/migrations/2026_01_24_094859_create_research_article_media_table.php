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
        Schema::create('research_article_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_article_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('media_type', ['image', 'document', 'video', 'other'])->default('image');
            $table->integer('sort_order')->default(0);
            $table->text('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->timestamps();

            $table->foreign('research_article_id')->references('id')->on('research_articles')->onDelete('cascade');
            $table->index('research_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_article_media');
    }
};
