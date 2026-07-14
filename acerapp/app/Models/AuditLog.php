<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'document_id',
        'policy_id',
        'auditable_id',
        'auditable_type',
        'model_name',
        'action',
        'old_data',
        'new_data',
        'description',
        'ip_address',
        'user_agent',
        'performed_by',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'old_data' => 'array',
            'new_data' => 'array',
            'performed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent auditable model (polymorphic relation).
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Document relationship (backward compatibility)
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Policy relationship (backward compatibility)
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * User who performed the action
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Scope to filter by model type
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('model_name', $modelType)
            ->orWhere('auditable_type', $modelType);
    }

    /**
     * Scope to filter by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('performed_by', $userId);
    }

    /**
     * Get human readable action name
     */
    public function getActionNameAttribute(): string
    {
        return match($this->action) {
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            default => ucfirst($this->action),
        };
    }
}
