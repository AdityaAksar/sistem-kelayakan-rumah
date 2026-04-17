<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            static::logActivity($model, 'updated', $model->getOriginal());
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'deleted', $model->getOriginal());
        });
    }

    protected static function logActivity($model, $action, $oldValues = null)
    {
        $newValues = null;

        if ($action === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'updated') {
            $newValues = $model->getChanges();
            
            // Format $oldValues to only contain keys that were changed
            if ($oldValues) {
                $oldValuesArr = [];
                foreach (array_keys($newValues) as $key) {
                    if (array_key_exists($key, $oldValues)) {
                        $oldValuesArr[$key] = $oldValues[$key];
                    }
                }
                $oldValues = $oldValuesArr;
            }
        } elseif ($action === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        // Jangan log perubahan yang tidak ada (untuk update trigger tapi gada field berubah)
        if ($action === 'updated' && empty($newValues)) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(), // null jika guest (from CLI/seed, atau public simulation)
            'action' => $action,
            'target_table' => $model->getTable(),
            'target_id' => $model->id ?? 0,
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
        ]);
    }
}
