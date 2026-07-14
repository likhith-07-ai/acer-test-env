<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchArticleMeta extends Model
{
    protected $table = 'research_article_meta';

    protected $fillable = [
        'research_article_id',
        'meta_key',
        'meta_value',
    ];

    /**
     * Get the article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(ResearchArticle::class, 'research_article_id');
    }
}
