<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Policy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log model creation (generic)
     */
    public function logCreate(Model $model, ?string $description = null): void
    {
        $this->logActivity($model, 'create', null, $model->getAttributes(), $description);
    }

    /**
     * Log model update (generic)
     */
    public function logUpdate(Model $model, array $oldData, ?string $description = null): void
    {
        $this->logActivity($model, 'update', $oldData, $model->getAttributes(), $description);
    }

    /**
     * Log model deletion (generic)
     */
    public function logDelete(Model $model, ?string $description = null): void
    {
        $this->logActivity($model, 'delete', $model->getAttributes(), null, $description);
    }

    /**
     * Generic log activity method
     */
    protected function logActivity(Model $model, string $action, ?array $oldData = null, ?array $newData = null, ?string $description = null): void
    {
        if (!Auth::check()) {
            return;
        }

        // Remove sensitive fields
        $hiddenFields = ['password', 'remember_token', 'api_token'];
        if ($oldData) {
            $oldData = array_diff_key($oldData, array_flip($hiddenFields));
        }
        if ($newData) {
            $newData = array_diff_key($newData, array_flip($hiddenFields));
        }

        // Generate description if not provided
        if (!$description) {
            $modelName = class_basename($model);
            $identifier = $this->getModelIdentifier($model);
            $description = match($action) {
                'create' => "{$modelName} '{$identifier}' was created",
                'update' => "{$modelName} '{$identifier}' was updated",
                'delete' => "{$modelName} '{$identifier}' was deleted",
                default => "{$modelName} '{$identifier}' was {$action}",
            };
        }

        AuditLog::create([
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),
            'model_name' => class_basename($model),
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => $description,
            'performed_by' => Auth::id(),
            'performed_at' => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            // Backward compatibility
            'document_id' => $model instanceof Document ? $model->id : null,
            'policy_id' => $model instanceof Policy ? $model->id : null,
        ]);
    }

    /**
     * Get model identifier for description
     */
    protected function getModelIdentifier(Model $model): string
    {
        if (isset($model->name)) {
            return $model->name;
        }
        if (isset($model->title)) {
            return $model->title;
        }
        if (isset($model->email)) {
            return $model->email;
        }
        return "ID: {$model->id}";
    }

    /**
     * Log document creation (backward compatibility)
     */
    public function logDocumentCreate(Document $document): void
    {
        $this->logCreate($document);
    }

    /**
     * Log document update (backward compatibility)
     */
    public function logDocumentUpdate(Document $document, array $oldData): void
    {
        $this->logUpdate($document, $oldData);
    }

    /**
     * Log document deletion (backward compatibility)
     */
    public function logDocumentDelete(Document $document): void
    {
        $this->logDelete($document);
    }

    /**
     * Log policy creation (backward compatibility)
     */
    public function logPolicyCreate(Policy $policy): void
    {
        $this->logCreate($policy);
    }

    /**
     * Log policy update (backward compatibility)
     */
    public function logPolicyUpdate(Policy $policy, array $oldData): void
    {
        $this->logUpdate($policy, $oldData);
    }

    /**
     * Log policy deletion (backward compatibility)
     */
    public function logPolicyDelete(Policy $policy): void
    {
        $this->logDelete($policy);
    }
}

