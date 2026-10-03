<?php

namespace App\Console\Commands;

use App\Services\Sync\SyncEngine;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('gesco:sync')]
#[Description('Synchronise la base locale GESCO avec le Cloud')]
class SyncGESCO extends Command
{
    public function handle(SyncEngine $syncEngine): int
    {
        if (!config('sync.enabled')) {
            $this->components->warn(
                'La synchronisation GESCO est désactivée (SYNC_ENABLED=false).'
            );

            return self::SUCCESS;
        }

        $this->components->info(
            'Démarrage de la synchronisation GESCO...'
        );

        try {
            $result = $syncEngine->sync();

            if (($result['success'] ?? false) === true) {
                $this->components->info(
                    'Synchronisation GESCO terminée avec succès.'
                );

                $this->line(
                    'Push : '
                    . ($result['push']['status'] ?? 'inconnu')
                    . ' | '
                    . 'Pull : '
                    . ($result['pull']['status'] ?? 'inconnu')
                );

                return self::SUCCESS;
            }

            $this->components->error(
                'La synchronisation GESCO s’est terminée avec des erreurs.'
            );

            return self::FAILURE;
        } catch (Throwable $e) {
            $this->components->error(
                'Erreur de synchronisation : ' . $e->getMessage()
            );

            report($e);

            return self::FAILURE;
        }
    }
}