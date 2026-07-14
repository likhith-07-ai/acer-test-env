<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Loggable
{
    /**
     * Boot the trait
     */
    public static function bootLoggable()
    {
        static::created(function ($model) {
            static::logActivity($model, 'create');
        });

        static::updated(function ($model) {
            static::logActivity($model, 'update');
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'delete');
        });
    }

    /**
     * Log activity for the model
     */
    protected static function logActivity($model, $action)
    {
        if (!Auth::check()) {
            return;
        }

        $oldData = null;
        $newData = null;

        if ($action === 'create') {
            $newData = $model->getAttributes();
        } elseif ($action === 'update') {
            $oldData = $model->getOriginal();
            $newData = $model->getAttributes();
        } elseif ($action === 'delete') {
            $oldData = $model->getAttributes();
        }

        // Remove sensitive fields
        $hiddenFields = ['password', 'remember_token', 'api_token'];
        if ($oldData) {
            $oldData = array_diff_key($oldData, array_flip($hiddenFields));
        }
        if ($newData) {
            $newData = array_diff_key($newData, array_flip($hiddenFields));
        }

        AuditLog::create([
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),
            'model_name' => class_basename($model),
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => static::getActivityDescription($model, $action),
            'performed_by' => Auth::id(),
            'performed_at' => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get activity description
     */
    protected static function getActivityDescription($model, $action): string
    {
        $modelName = class_basename($model);
        $identifier = static::getModelIdentifier($model);

        return match($action) {
            'create' => "{$modelName} '{$identifier}' was created",
            'update' => "{$modelName} '{$identifier}' was updated",
            'delete' => "{$modelName} '{$identifier}' was deleted",
            default => "{$modelName} '{$identifier}' was {$action}",
        };
    }

    /**
     * Get model identifier for description (name, title, etc.)
     */
    protected static function getModelIdentifier($model): string
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
     * Get audit logs for this model
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}

