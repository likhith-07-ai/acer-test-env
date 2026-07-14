<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchArticleMedia extends Model
{
    protected $fillable = [
        'research_article_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'mime_type',
        'media_type',
        'sort_order',
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(ResearchArticle::class, 'research_article_id');
    }
}
