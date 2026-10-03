<?php

namespace App\Services\Sync;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SyncApiService
{
    protected function client()
    {
        $url = rtrim((string) config('sync.url'), '/');
        $token = (string) config('sync.token');
        $timeout = (int) config('sync.timeout', 30);

        if ($url === '') {
            throw new RuntimeException(
                'SYNC_URL n’est pas configurée.'
            );
        }

        if ($token === '') {
            throw new RuntimeException(
                'SYNC_TOKEN n’est pas configuré.'
            );
        }

        return Http::timeout($timeout)
            ->acceptJson()
            ->withToken($token);
    }

    public function bootstrap(array $payload): array
    {
        /** @var Response $response */
        $response = $this->client()
            ->post(
                rtrim((string) config('sync.url'), '/')
                . '/api/sync/bootstrap',
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        throw new RuntimeException(
            'Erreur API bootstrap HTTP '
            . $response->status()
            . ' : '
            . $response->body()
        );
    }

    public function push(array $batch): array
    {
        /** @var Response $response */
        $response = $this->client()
            ->post(
                rtrim((string) config('sync.url'), '/')
                . '/api/sync/push',
                $batch
            );

        if ($response->successful()) {
            return $response->json();
        }

        throw new RuntimeException(
            'Erreur API de synchronisation HTTP '
            . $response->status()
            . ' : '
            . $response->body()
        );
    }

    public function pull(string $deviceId, int $lastOperationId = 0, int $limit = 100): array
    {
        $response = $this->client()
            ->post(
                rtrim((string) config('sync.url'), '/')
                . '/api/sync/pull',
                [
                    'device_id' => $deviceId,
                    'last_operation_id' => $lastOperationId,
                    'limit' => $limit,
                ]
            );

        if ($response->successful()) {
            return $response->json();
        }

        throw new RuntimeException(
            'Erreur API de récupération HTTP '
            . $response->status()
            . ' : '
            . $response->body()
        );
    }
}