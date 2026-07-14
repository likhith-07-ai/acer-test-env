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
        Schema::create('research_article_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_article_id');
            $table->string('meta_key');
            $table->text('meta_value')->nullable();
            $table->timestamps();

            $table->foreign('research_article_id')->references('id')->on('research_articles')->onDelete('cascade');
            $table->unique(['research_article_id', 'meta_key']);
            $table->index('meta_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_article_meta');
    }
};
