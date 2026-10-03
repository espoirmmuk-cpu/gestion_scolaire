<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'details_paiements',
            'evaluations',
            'notes',
            'bulletins',
            'details_bulletins',
            'presences',
            'activites',
            'depenses',
            'recettes',
            'infrastructures',
            'inventaire',
            'classes_matieres',
            'affectations_enseignants',
            'eleves_responsables',
            'utilisateurs',
            'journaux_activites',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->uuid('uuid_sync')->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'details_paiements',
            'evaluations',
            'notes',
            'bulletins',
            'details_bulletins',
            'presences',
            'activites',
            'depenses',
            'recettes',
            'infrastructures',
            'inventaire',
            'classes_matieres',
            'affectations_enseignants',
            'eleves_responsables',
            'utilisateurs',
            'journaux_activites',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('uuid_sync');
            });
        }
    }
};