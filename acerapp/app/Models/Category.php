<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'short_description',
        'parent_id',
    ];

    /**
     * Parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Sub categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Documents in this category
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    /**
     * Documents in this sub-category
     */
    public function subCategoryDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'sub_category_id');
    }

    /**
     * Check if category is a sub-category
     */
    public function isSubCategory(): bool
    {
        return $this->parent_id !== null;
    }
}
