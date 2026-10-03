<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Syncable
{
    /**
     * Champs qui ne doivent jamais être envoyés
     * dans les données de synchronisation.
     */
    protected function getSyncHidden(): array
    {
        return property_exists($this, 'syncHidden')
            ? $this->syncHidden
            : [];
    }

    /**
     * Attributs préparés pour la synchronisation.
     */
    protected function getSyncPayload(): array
    {
        $attributes = collect($this->getAttributes())
            ->except($this->getSyncHidden())
            ->toArray();

        return app(\App\Services\Sync\SyncPayloadBuilder::class)
            ->build($this->getTable(), $attributes);
    }

    /**
     * Activation du suivi des modifications.
     */
    protected static function bootSyncable(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid_sync)) {
                $model->uuid_sync = (string) Str::uuid();
            }
        });

        static::created(function ($model) {
            $model->recordSyncOperation('create');
        });

        static::updated(function ($model) {
            $model->recordSyncOperation('update');
        });

        static::deleting(function ($model) {
            $model->recordSyncOperation('delete');
        });
    }

    /**
     * Enregistre une opération de synchronisation.
     */
    protected function recordSyncOperation(string $operation): void
    {
        if (empty($this->uuid_sync)) {
            return;
        }

        $payload = $operation === 'delete'
            ? [
                'uuid_sync' => $this->uuid_sync,
                'id' => $this->getKey(),
            ]
            : $this->getSyncPayload();

        DB::table('sync_operations')->insert([
            'uuid_operation' => (string) Str::uuid(),
            'source_device_id' => app(\App\Services\Sync\DeviceIdentity::class)->get(),
            'table_name' => $this->getTable(),
            'record_uuid' => $this->uuid_sync,
            'operation' => $operation,
            'payload' => json_encode($payload),
            'created_at' => now(),
            'synced_at' => null,
            'status' => 'pending',
            'error_message' => null,
        ]);
    }

    public function pull(Request $request)
    {
        $deviceId = (string) $request->input('device_id');

        if ($deviceId === '') {
            return response()->json([
                'success' => false,
                'message' => 'device_id est obligatoire.',
            ], 422);
        }

        $cursor = (int) $request->input('last_operation_id', 0);
        $limit = min((int) $request->input('limit', 100), 500);

        $operations = DB::connection('gesco_cloud')
            ->table('sync_operations')
            ->where('id', '>', $cursor)
            ->where(function ($query) use ($deviceId) {
                $query->whereNull('source_device_id')
                    ->orWhere('source_device_id', '!=', $deviceId);
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $nextCursor = $cursor;

        if ($operations->isNotEmpty()) {
            $nextCursor = $operations->max('id');
        }

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'cursor' => $cursor,
            'next_cursor' => $nextCursor,
            'operations' => $operations->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'uuid_operation' => $operation->uuid_operation,
                    'source_device_id' => $operation->source_device_id,
                    'table_name' => $operation->table_name,
                    'record_uuid' => $operation->record_uuid,
                    'operation' => $operation->operation,
                    'payload' => $operation->payload
                        ? json_decode($operation->payload, true)
                        : null,
                    'created_at' => $operation->created_at,
                ];
            })->values()->all(),
        ]);
    }
}