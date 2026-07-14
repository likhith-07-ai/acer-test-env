<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'regulator',
        'title',
        'description',
        'category_id',
        'sub_category_id',
        'access_type',
        'file_path',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Category relationship
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocCategory::class, 'category_id');
    }

    /**
     * Sub-category relationship
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(DocCategory::class, 'sub_category_id');
    }

    /**
     * User who created the document
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the document
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Audit logs for this document
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if document is restricted
     */
    public function isRestricted(): bool
    {
        return $this->access_type === 'restricted';
    }

    /**
     * Check if document is public
     */
    public function isPublic(): bool
    {
        return $this->access_type === 'public';
    }

    /**
     * Scope for public documents
     */
    public function scopePublic($query)
    {
        return $query->where('access_type', 'public');
    }

    /**
     * Scope for restricted documents
     */
    public function scopeRestricted($query)
    {
        return $query->where('access_type', 'restricted');
    }

    /**
     * Scope for SEBI documents
     */
    public function scopeSebi($query)
    {
        return $query->where('regulator', 'SEBI');
    }

    /**
     * Scope for RBI documents
     */
    public function scopeRbi($query)
    {
        return $query->where('regulator', 'RBI');
    }
}
