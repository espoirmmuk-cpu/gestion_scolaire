<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'etablissements',
            'annees_scolaires',
            'niveaux',
            'periodes_scolaires',
            'classes',
            'matieres',
            'personnel',
            'responsables',
            'categories_frais',
            'categories_inventaire',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->uuid('uuid_sync')->nullable()->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'etablissements',
            'annees_scolaires',
            'niveaux',
            'periodes_scolaires',
            'classes',
            'matieres',
            'personnel',
            'responsables',
            'categories_frais',
            'categories_inventaire',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('uuid_sync');
            });
        }
    }
};