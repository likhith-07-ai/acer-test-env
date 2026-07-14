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
        Schema::create('research_article_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('research_article_id');
            $table->unsignedBigInteger('research_tag_id');
            $table->timestamps();

            $table->foreign('research_article_id')->references('id')->on('research_articles')->onDelete('cascade');
            $table->foreign('research_tag_id')->references('id')->on('research_tags')->onDelete('cascade');
            
            $table->unique(['research_article_id', 'research_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_article_tag');
    }
};
