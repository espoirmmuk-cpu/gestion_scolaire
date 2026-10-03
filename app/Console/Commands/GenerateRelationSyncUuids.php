<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateRelationSyncUuids extends Command
{
    protected $signature = 'sync:generate-relation-uuids';

    protected $description = 'Génère les UUID de synchronisation des tables de relations';

    public function handle(): int
    {
        $count = 0;

        DB::table('utilisateurs_roles')
            ->whereNull('uuid_sync')
            ->orderBy('id_utilisateur')
            ->orderBy('id_role')
            ->get()
            ->each(function ($relation) use (&$count) {
                DB::table('utilisateurs_roles')
                    ->where('id_utilisateur', $relation->id_utilisateur)
                    ->where('id_role', $relation->id_role)
                    ->update([
                        'uuid_sync' => (string) Str::uuid(),
                    ]);

                $count++;
            });

        $this->info("utilisateurs_roles : {$count} UUID généré(s).");

        $this->newLine();
        $this->info('Génération des UUID de relations terminée.');

        return self::SUCCESS;
    }
}